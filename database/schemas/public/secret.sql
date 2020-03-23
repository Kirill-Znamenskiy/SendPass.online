
--

CREATE TABLE secret(id SERIAL NOT NULL PRIMARY KEY CHECK (id > 0)
    , created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL
    , updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL
    , created_by_user_id INTEGER REFERENCES "user"(id)
    , updated_by_user_id INTEGER REFERENCES "user"(id)

    , uuid UUID NOT NULL UNIQUE

    , secpass VARCHAR(255) CHECK ((secpass IS NULL) OR (BTRIM(secpass) != ''))
    , sectext VARCHAR(11111) CHECK ((sectext IS NULL) OR (BTRIM(sectext) != ''))

    , is_allow_show_created BOOLEAN NOT NULL

    , crr_show_count INTEGER NOT NULL CHECK (crr_show_count >= 0)
    , max_show_count INTEGER NOT NULL CHECK (max_show_count > 0)
    , is_hide_show_count BOOLEAN NOT NULL

    , expired_at TIMESTAMP(0) WITH TIME ZONE NOT NULL
    , is_hide_lifetime BOOLEAN NOT NULL
);

--

ALTER TABLE secret DROP CONSTRAINT IF EXISTS "secret_check_about_secpasstext";
ALTER TABLE secret ADD CONSTRAINT "secret_check_about_secpasstext" CHECK (false
    OR (true
        AND (secpass IS NULL)
        AND (sectext IS NOT NULL) AND (BTRIM(sectext) != '')
    )
    OR (true
        AND (secpass IS NOT NULL) AND (BTRIM(secpass) != '')
        AND (sectext IS NULL)
    )
    OR (true
        AND (secpass IS NOT NULL) AND (BTRIM(secpass) != '')
        AND (sectext IS NOT NULL) AND (BTRIM(sectext) != '')
    )
);

ALTER TABLE secret DROP CONSTRAINT IF EXISTS "seclinks_check_about_shows";
ALTER TABLE secret ADD CONSTRAINT "seclinks_check_about_shows" CHECK (true
    AND (crr_show_count <= max_show_count)
);

ALTER TABLE secret DROP CONSTRAINT IF EXISTS "seclinks_check_about_expired_at";
ALTER TABLE secret ADD CONSTRAINT "seclinks_check_about_expired_at" CHECK (true
    AND (expired_at > created_at)
    AND (expired_at <= (created_at+(INTERVAL '6 month')))
);

--

DROP TRIGGER IF EXISTS "00_bru_raise_illegal_updates" ON secret;
CREATE TRIGGER "00_bru_raise_illegal_updates"
BEFORE UPDATE ON secret FOR EACH ROW
WHEN (NOT(true
    AND (OLD.id IS NOT DISTINCT FROM NEW.id)
    AND (OLD.created_at IS NOT DISTINCT FROM NEW.created_at)
    AND (OLD.created_by_user_id IS NOT DISTINCT FROM NEW.created_by_user_id)
    AND (OLD.uuid IS NOT DISTINCT FROM NEW.uuid)
    AND (OLD.secpass IS NOT DISTINCT FROM NEW.secpass)
    AND (OLD.sectext IS NOT DISTINCT FROM NEW.sectext)
    AND (false
        OR (OLD.is_allow_show_created IS NOT DISTINCT FROM NEW.is_allow_show_created)
        OR ((OLD.is_allow_show_created IS NOT DISTINCT FROM TRUE) AND (NEW.is_allow_show_created IS NOT DISTINCT FROM FALSE))
    )
    AND (false
        OR (OLD.crr_show_count IS NOT DISTINCT FROM NEW.crr_show_count)
        OR (OLD.crr_show_count = (NEW.crr_show_count-1))
    )
    AND (OLD.max_show_count IS NOT DISTINCT FROM NEW.max_show_count)
    AND (OLD.expired_at IS NOT DISTINCT FROM NEW.expired_at)
))
EXECUTE PROCEDURE kz_trbriud_raise();

CREATE TRIGGER "10_briu_kz_trbriu_set_created_updated_at"
BEFORE INSERT OR UPDATE ON secret FOR EACH ROW
EXECUTE PROCEDURE kz_trbriu_set_created_updated_at();

CREATE TRIGGER "10_briu_kz_trbriu_set_created_updated_by_user_id"
BEFORE INSERT OR UPDATE ON secret FOR EACH ROW
EXECUTE PROCEDURE kz_trbriu_set_created_updated_by_user_id('ALLOW_NULL');

--
