
--

CREATE TABLE users(id SERIAL NOT NULL PRIMARY KEY CHECK (id > 0)
    , created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL
    , updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL
    , created_by_user_id INTEGER NOT NULL REFERENCES "user"(id)
    , updated_by_user_id INTEGER NOT NULL REFERENCES "user"(id)

    , is_admin BOOLEAN NOT NULL DEFAULT FALSE
    , key VARCHAR(255) UNIQUE CHECK ((key IS NULL) OR (key ~ '^[A-Z0-9_]+'))
    , phone VARCHAR(64) UNIQUE CHECK ((phone IS NULL) OR (phone ~ '^\d{9,12}$'))
    , email VARCHAR(255) UNIQUE CHECK ((email IS NULL) OR (email ~ '^[-\w\.]+\@([-[:alnum:]]+\.)+[[:alnum:]]+$'))
    , auth_name VARCHAR(64) UNIQUE CHECK ((auth_name IS NULL) OR (auth_name ~ '^[[:alpha:]][-\w]+$'))
    , first_name VARCHAR(255) NOT NULL CHECK (first_name ~ '^[[:alpha:]][-\w]+$')
    , last_name VARCHAR(255) NOT NULL CHECK (last_name ~ '^[[:alpha:]][-\w]+$')
    , patronymic VARCHAR(255) CHECK ((patronymic IS NULL) OR (patronymic ~ '^[-\w]+$'))

    , password_hash VARCHAR(255) CHECK ((password_hash IS NULL) OR (BTRIM(password_hash) != ''))
    , password_updated_at TIMESTAMP(0) WITH TIME ZONE

    , email_verified_at TIMESTAMP(0) WITH TIME ZONE
    , lastime_logged_in_at TIMESTAMP(0) WITH TIME ZONE
    , remember_token VARCHAR(100)

    , blocked_at TIMESTAMP(0) WITH TIME ZONE
    , is_super BOOLEAN NOT NULL DEFAULT FALSE

    , comment VARCHAR(999)
);

--

ALTER TABLE "user" ADD UNIQUE (first_name, last_name);

--

ALTER TABLE "user" DROP CONSTRAINT IF EXISTS "users_check_about_password_hash";
ALTER TABLE "user" ADD CONSTRAINT "users_check_about_password_hash" CHECK (false
    OR (true
        AND (password_hash IS NULL)
        AND (password_updated_at IS NULL)
    )
    OR (true
        AND (password_hash IS NOT NULL)
        AND (password_updated_at IS NOT NULL)
    )
);

--

INSERT INTO "user"(id, created_by_user_id, updated_by_user_id
    , created_at, updated_at
    , key, first_name, last_name
    , comment
)
VALUES (88, 88, 88
    , CURRENT_TIMESTAMP(0), CURRENT_TIMESTAMP(0)
    , 'DB', 'DB', 'DB'
    , ('db user "'||CURRENT_USER||'"')
);

ALTER SEQUENCE users_id_seq START WITH 111;
ALTER SEQUENCE users_id_seq RESTART;

--

DROP TRIGGER IF EXISTS "00_bru_raise_illegal_updates" ON "user";
CREATE TRIGGER "00_bru_raise_illegal_updates"
BEFORE UPDATE ON "user" FOR EACH ROW
WHEN (NOT(true
    AND (OLD.id IS NOT DISTINCT FROM NEW.id)
    AND (false
        OR (OLD.key IS NULL)
        OR (OLD.key IS DISTINCT FROM NEW.key)
    )
    AND (false
        OR (OLD.password_updated_at IS NULL)
        OR (true
            AND (OLD.password_updated_at IS NOT NULL)
            AND (NEW.password_updated_at IS NOT NULL)
            AND (OLD.password_updated_at <= NEW.password_updated_at)
        )
    )
    AND (false
        OR (OLD.lastime_logged_in_at IS NULL)
        OR (true
            AND (OLD.lastime_logged_in_at IS NOT NULL)
            AND (NEW.lastime_logged_in_at IS NOT NULL)
            AND (OLD.lastime_logged_in_at <= NEW.lastime_logged_in_at)
        )
    )
))
EXECUTE PROCEDURE kz_trbriud_raise();

CREATE TRIGGER "10_briu_kz_trbriu_set_created_updated_at"
BEFORE INSERT OR UPDATE ON "user" FOR EACH ROW
EXECUTE PROCEDURE kz_trbriu_set_created_updated_at();

CREATE TRIGGER "10_briu_kz_trbriu_set_created_updated_by_user_id"
BEFORE INSERT OR UPDATE ON "user" FOR EACH ROW
EXECUTE PROCEDURE kz_trbriu_set_created_updated_by_user_id();

CREATE OR REPLACE FUNCTION users_trbriu_func() RETURNS TRIGGER AS $$

    BEGIN

        IF (TG_WHEN != 'BEFORE') THEN RAISE '(TG_WHEN != BEFORE)'; END IF;
        IF (TG_LEVEL != 'ROW') THEN RAISE '(TG_WHEN != ROW)'; END IF;
        IF (TG_TABLE_NAME != 'users') THEN RAISE '(TG_TABLE_NAME != users)'; END IF;
        IF (TG_NARGS != 0) THEN RAISE '(TG_NARGS != 0)'; END IF;

        IF (TG_OP = 'INSERT') THEN
            NEW.password_updated_at := (CASE WHEN (NEW.password_hash IS NULL) THEN NULL ELSE CURRENT_TIMESTAMP(0) END);
        ELSIF (TG_OP = 'UPDATE') THEN

            IF (OLD.password_hash IS DISTINCT FROM NEW.password_hash) THEN
                NEW.password_updated_at := (CASE WHEN (NEW.password_hash IS NULL) THEN NULL ELSE CURRENT_TIMESTAMP(0) END);
            ELSIF (OLD.password_hash IS NOT DISTINCT FROM NEW.password_hash) THEN
                NEW.password_updated_at := OLD.password_updated_at;
            ELSE
                RAISE EXCEPTION 'Что-то тут совсем не то!';
            END IF;

        ELSE RAISE '(TG_OP != INSERT,UPDATE)';
        END IF;

        RETURN NEW;

    END

$$ LANGUAGE plpgsql VOLATILE;
DROP TRIGGER IF EXISTS "20_briu_users_trbriu_func" ON "user";
CREATE TRIGGER "20_briu_users_trbriu_func"
BEFORE INSERT OR UPDATE ON "user" FOR EACH ROW
EXECUTE PROCEDURE user_trbriu_func();

--

INSERT INTO "user"(id
    , key, first_name, last_name
    , comment
)
VALUES (36
    , 'PHP_CONSOLE_ARTISAN', 'PHP_CONSOLE_ARTISAN', 'PHP_CONSOLE_ARTISAN'
    , 'php console artisan user'
);




