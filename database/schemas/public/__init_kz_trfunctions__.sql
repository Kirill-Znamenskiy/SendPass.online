--
-- здесь собраны триггерные функции, написанные на любом доступном языке
-- нюансы их реализации и/или использования так или иначе связаны с обобщенным,
-- мультипроектным функционалом предоставляемым в пакетах kirill-znamenskiy/*,
-- их название начинается с префикса kz_
--

CREATE OR REPLACE FUNCTION kz_trbriud_none() RETURNS TRIGGER AS $$
BEGIN
    IF ((TG_OP = 'INSERT') OR (TG_OP = 'UPDATE')) THEN
       RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
       RETURN OLD;
    ELSE
       RAISE '!!!';
    END IF;
END
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION kz_trbriud_raise() RETURNS TRIGGER AS $$
DECLARE
    msg TEXT;
    oo TEXT;
    nn TEXT;
BEGIN

    CASE TG_OP
        WHEN 'INSERT' THEN
            nn := 'NEW='||NEW||'; ';
            oo := 'OLD=NULL; ';
        WHEN 'UPDATE' THEN
            nn := 'NEW='||NEW||'; ';
            oo := 'OLD='||OLD||'; ';
        WHEN 'DELETE' THEN
            nn := 'NEW=NULL; ';
            oo := 'OLD='||OLD||'; ';
        ELSE
            RAISE '!!!';
    END CASE;

    msg := 'Что-то не так! '
    || 'TG_TABLE_NAME='||TG_TABLE_NAME||'; '
    || 'TG_NAME='||TG_NAME||'; '
    || 'TG_LEVEL='||TG_LEVEL||'; '
    || 'TG_OP='||TG_OP||'; '
    || 'TG_WHEN='||TG_WHEN||'; '
    || oo
    || nn;


    RAISE EXCEPTION '%', msg;
END
$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION kz_trbriud_raise_updates() RETURNS TRIGGER AS $$
    DECLARE
        sql_stmt TEXT;
        whh TEXT;
        msg TEXT;
    BEGIN
        IF (TG_OP = 'INSERT') THEN
            msg := 'Запрещено изменение данных, в том числе вставка новых записей! (NEW='||NEW||')';
            RAISE EXCEPTION '%', msg;
        ELSIF (TG_OP = 'DELETE') THEN
            msg := 'Запрещено изменение данных, в том числе удаление записей! (OLD='||OLD||')';
            RAISE EXCEPTION '%', msg;
        ELSEIF (TG_OP = 'UPDATE') THEN

            SELECT
                STRING_AGG((CASE

                    WHEN (ttt.specvar IS NOT DISTINCT FROM 'check_if_isset')
                        THEN '((ooo.'||quote_ident(attname)||' IS NULL) OR (ooo.'||quote_ident(attname)||' IS NOT DISTINCT FROM nnn.'||quote_ident(attname)||'))'

                    WHEN (atttypid = /*JSON*/114)
                        THEN '(ooo.'||quote_ident(attname)||'::TEXT IS NOT DISTINCT FROM nnn.'||quote_ident(attname)||'::TEXT)'

                    ELSE '(ooo.'||quote_ident(attname)||' IS NOT DISTINCT FROM nnn.'||quote_ident(attname)||')'

                END),' AND ') AS whh
            INTO whh
            FROM
                pg_catalog.pg_attribute AS att
                LEFT JOIN (
                    SELECT colname AS orig_colname
                        , BTRIM(colname) AS colname
                        , LTRIM(BTRIM(colname),'!%') AS clear_colname
                        , (CASE SUBSTR(BTRIM(colname),1,1)
                            WHEN '!' THEN 'skip'
                            WHEN '%' THEN 'check_if_isset'
                            ELSE NULL
                        END) AS specvar
                    FROM UNNEST(TG_ARGV) AS fff(colname)
                    WHERE (colname != '')
                ) AS ttt ON (att.attname = ttt.clear_colname)
            WHERE  (true
                AND (attrelid = TG_RELID)
                AND (attnum > 0)
                AND (attisdropped = FALSE)
                AND (ttt.specvar IS DISTINCT FROM 'skip')
            );

            sql_stmt := '
                SELECT ''stop_update''
                FROM
                    (SELECT $1.*) AS ooo INNER JOIN (SELECT $2.*) AS nnn ON (TRUE)
                WHERE (NOT('||whh||'))
                LIMIT 1
            ';

            EXECUTE sql_stmt INTO msg USING OLD,NEW;

            IF (msg IS NOT NULL) THEN
                msg := 'Запрещено изменение данных, в том числе обновление записей! (OLD='||OLD||'), (NEW='||NEW||'), (TG_NAME='||TG_NAME||'), (TG_LEVEL='||TG_LEVEL||'), (TG_OP='||TG_OP||')';
                RAISE EXCEPTION '%', msg;
            END IF;

            RETURN NEW;
        ELSE
            RAISE '!!!';
        END IF;
    END

$$ LANGUAGE plpgsql IMMUTABLE;

--

CREATE OR REPLACE FUNCTION kz_trbriu_set_created_updated_at() RETURNS TRIGGER AS $$

    DECLARE
        msg TEXT;
        ctstz TIMESTAMP WITH TIME ZONE;
    BEGIN

        ctstz := CURRENT_TIMESTAMP;

        IF (TG_OP = 'INSERT') THEN

            IF (TG_ARGV[0] IS NOT DISTINCT FROM 'STRICT') THEN
                IF (NEW.created_at IS NOT NULL) THEN RAISE EXCEPTION '(NEW.created_at IS NOT NULL)'; END IF;
                IF (NEW.updated_at IS NOT NULL) THEN RAISE EXCEPTION '(NEW.updated_at IS NOT NULL)'; END IF;
            END IF;

            IF (NEW.created_at IS NULL) THEN NEW.created_at = ctstz;
            ELSEIF (EXTRACT(EPOCH FROM (ctstz - NEW.created_at)) > 3) THEN
                RAISE EXCEPTION '((NEW.created_at IS NOT NULL) AND ((CURRENT_TIMESTAMP - NEW.created_at) > 3sec))';
            END IF;

            IF (NEW.updated_at IS NULL) THEN NEW.updated_at = ctstz;
            ELSEIF (EXTRACT(EPOCH FROM (ctstz - NEW.updated_at)) > 3) THEN
                RAISE EXCEPTION '((NEW.updated_at IS NOT NULL) AND ((CURRENT_TIMESTAMP - NEW.updated_at) > 3sec))';
            END IF;

        ELSEIF (TG_OP = 'UPDATE') THEN


            IF (false
                OR (TG_ARGV[1] IS NOT DISTINCT FROM 'STRICT')
                OR ((TG_ARGV[0] IS NOT DISTINCT FROM 'STRICT') AND (TG_ARGV[1] IS NULL))
            ) THEN
                IF (NEW.created_at IS DISTINCT FROM OLD.created_at) THEN
                    msg := '(NEW.created_at IS DISTINCT FROM OLD.created_at) OLD.created_at='''||OLD.created_at||''' NEW.created_at='''||NEW.created_at||'''';
                    RAISE EXCEPTION '%', msg;
                END IF;
                IF (NEW.updated_at IS DISTINCT FROM OLD.updated_at) THEN
                    msg := '(NEW.updated_at IS DISTINCT FROM OLD.updated_at) OLD.updated_at='''||OLD.updated_at||''' NEW.updated_at='''||NEW.updated_at||'''';
                    RAISE EXCEPTION '%', msg;
                END IF;
            END IF;

            NEW.created_at = OLD.created_at;

            -- Выдает ошибку, т.к. в PostgreSQL не реализовано сравнение полей типа JSON и соответственно когда в таблице
            -- есть хотябы одно поле типа JSON то у PostgreSQL не получается сравнить кортежи OLD и NEW
            -- IF (OLD IS DISTINCT FROM NEW) THEN
                IF (NEW.updated_at IS NULL) THEN NEW.updated_at = ctstz;
                ELSEIF (NEW.updated_at IS NOT DISTINCT FROM OLD.updated_at) THEN NEW.updated_at = ctstz;
                ELSEIF (EXTRACT(EPOCH FROM (ctstz - NEW.updated_at)) > 3) THEN
                    RAISE EXCEPTION '((NEW.updated_at IS NOT NULL) AND (NEW.updated_at IS NOT DISTINCT FROM OLD.updated_at) AND ((CURRENT_TIMESTAMP - NEW.updated_at) > 3sec))';
                END IF;
            -- END IF;
        ELSE
            RAISE EXCEPTION 'Allow set created_at and updated_at just at insert or update!';
        END IF;

        RETURN NEW;
    END

$$ LANGUAGE plpgsql VOLATILE;

--

CREATE OR REPLACE FUNCTION kz_trbriu_set_created_updated_by_user_id() RETURNS TRIGGER AS $$



    DECLARE
        current_user_id INTEGER;
    BEGIN

        current_user_id := CURRENT_SETTING('kz.current_user_id',TRUE);
        if (current_user_id <= 0) THEN current_user_id := NULL; END IF;

        IF (current_user_id IS NULL) THEN
            IF (TG_ARGV[0] IS DISTINCT FROM 'ALLOW_NULL') THEN
                IF (CURRENT_USER = CURRENT_DATABASE()) THEN
                    current_user_id := 88;
                ELSE
                    RAISE EXCEPTION 'Не удалось определить текущего пользователя!';
                END IF;
            END IF;
        END IF;


        IF (current_user_id IS NOT NULL) THEN
            SELECT id
            FROM "user"
            INTO current_user_id
            WHERE (true
               AND (id = current_user_id)
            );

            IF (current_user_id IS NULL) THEN
                RAISE EXCEPTION '!!!';
            END IF;

        END IF;

        IF (TG_OP = 'INSERT') THEN
            IF (NEW.created_by_user_id IS NOT NULL) THEN RAISE EXCEPTION '(NEW.created_by_user_id IS NOT NULL)'; END IF;
            IF (NEW.updated_by_user_id IS NOT NULL) THEN RAISE EXCEPTION '(NEW.updated_by_user_id IS NOT NULL)'; END IF;
            NEW.created_by_user_id = current_user_id;
            NEW.updated_by_user_id = current_user_id;
        ELSEIF (TG_OP = 'UPDATE') THEN
            NEW.created_by_user_id = OLD.created_by_user_id;
            -- Выдает ошибку, т.к. в PostgreSQL не реализовано сравнение полей типа JSON
            IF (OLD IS DISTINCT FROM NEW) THEN
                NEW.updated_by_user_id = current_user_id;
            END IF;
        ELSE
            RAISE EXCEPTION 'Allow set created_by_user_id and updated_by_user_id just at insert or update!';
        END IF;

        RETURN NEW;

    END



$$ LANGUAGE plpgsql VOLATILE;

--

CREATE OR REPLACE FUNCTION kz_trbriu_set_lock_version() RETURNS TRIGGER AS $$

BEGIN

    IF (TG_OP = 'INSERT') THEN
        NEW.lock_version = 1;
        RETURN NEW;
    ELSEIF (TG_OP = 'UPDATE') THEN
        IF (NEW.lock_version IS DISTINCT FROM OLD.lock_version) THEN
            RAISE EXCEPTION 'Lock version is distinct!';
        END IF;
        NEW.lock_version = COALESCE(OLD.lock_version,0) + 1;
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Allow set lock_version just at insert or update!';
    END IF;

END

$$ LANGUAGE plpgsql VOLATILE;


