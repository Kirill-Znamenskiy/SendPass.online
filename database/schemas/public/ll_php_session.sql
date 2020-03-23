
--

CREATE TABLE ll_php_session(id VARCHAR(255) NOT NULL PRIMARY KEY
    , user_id INTEGER REFERENCES "user"(id)
    , ip_address VARCHAR(45)
    , user_agent TEXT
    , payload TEXT NOT NULL
    , last_activity INTEGER NOT NULL
);

--



