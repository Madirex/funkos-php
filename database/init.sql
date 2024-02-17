SELECT 'CREATE DATABASE funkosDb' WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'shop');

DROP TABLE IF EXISTS "funkos";
DROP SEQUENCE IF EXISTS funkos_id_seq;
DROP TABLE IF EXISTS "user_roles";
DROP TABLE IF EXISTS "users";
DROP SEQUENCE IF EXISTS users_id_seq;
DROP TABLE IF EXISTS "categories";

CREATE SEQUENCE funkos_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 6 CACHE 1;

CREATE TABLE "public"."funkos"
(
    "is_deleted"  boolean          DEFAULT false,
    "price"       double precision DEFAULT '0.0',
    "stock"       integer          DEFAULT '0',
    "created_at"  timestamp        DEFAULT CURRENT_TIMESTAMP        NOT NULL,
    "id"          bigint           DEFAULT nextval('funkos_id_seq') NOT NULL,
    "updated_at"  timestamp        DEFAULT CURRENT_TIMESTAMP        NOT NULL,
    "category_id" uuid,
    "uuid"        uuid                                              NOT NULL,
    "description" character varying(255),
    "image"       text             DEFAULT 'images/funkos.bmp',
    CONSTRAINT "funkos_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "funkos_uuid_key" UNIQUE ("uuid")
) WITH (oids = false);

INSERT INTO "funkos" ("is_deleted", "price", "stock", "created_at", "id", "updated_at", "category_id", "uuid",
                      "description", "image")
VALUES ('f', 10.99, 5, '2023-11-02 11:43:24.722473', 1, '2023-11-02 11:43:24.722473',
        'd69cf3db-b77d-4181-b3cd-5ca8107fb6a9', '19135792-b778-441f-871e-d6e6096e0ddc', 'Funko Batman',
        'images/batman.png'),
       ('f', 19.99, 10, '2023-11-03 09:21:17.835912', 2, '2023-11-03 09:21:17.835912',
        '6dbcbf5e-8e1c-47cc-8578-7b0a33ebc154', '662ed342-de99-45c6-8463-446989aab9c8', 'Funko Iron Man',
        'images/ironman.png'),
       ('f', 15.99, 2, '2023-11-04 15:07:59.123456', 3, '2023-11-04 15:07:59.123456',
        'd69cf3db-b77d-4181-b3cd-5ca8107fb6a9', 'b79182ad-91c3-46e8-90b9-268164596a72', 'Funko Spider-Man',
        'images/spiderman.png'),
       ('f', 25.99, 8, '2023-11-05 18:36:42.987654', 4, '2023-11-05 18:36:42.987654',
        '6dbcbf5e-8e1c-47cc-8578-7b0a33ebc154', '4fa72b3f-dca2-4fd8-b803-dffacf148c10', 'Funko Darth Vader',
        'images/darthvader.png'),
       ('f', 12.99, 3, '2023-11-06 12:45:33.567890', 5, '2023-11-06 12:45:33.567890',
        '6dbcbf5e-8e1c-47cc-8578-7b0a33ebc154', '1e2584d8-db52-45da-b2d6-4203637ea78e', 'Funko Harry Potter',
        'images/harry.png');


CREATE TABLE "public"."user_roles"
(
    "user_id" bigint NOT NULL,
    "roles"   character varying(255)
) WITH (oids = false);

INSERT INTO "user_roles" ("user_id", "roles")
VALUES (1, 'USER'),
       (1, 'ADMIN'),
       (2, 'USER'),
       (2, 'USER'),
       (3, 'USER');

CREATE SEQUENCE users_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 5 CACHE 1;

CREATE TABLE "public"."users"
(
    "is_deleted" boolean   DEFAULT false,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP       NOT NULL,
    "id"         bigint    DEFAULT nextval('users_id_seq') NOT NULL,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP       NOT NULL,
    "surnames"   character varying(255)                    NOT NULL,
    "email"      character varying(255)                    NOT NULL,
    "name"       character varying(255)                    NOT NULL,
    "password"   character varying(255)                    NOT NULL,
    "username"   character varying(255)                    NOT NULL,
    CONSTRAINT "users_email_key" UNIQUE ("email"),
    CONSTRAINT "users_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "users_username_key" UNIQUE ("username")
) WITH (oids = false);

-- Contraseña: admin Admin1
-- Contraseña: user User1234
-- Contraseña: test test1234

INSERT INTO "users" ("is_deleted", "created_at", "id", "updated_at", "surnames", "email", "name", "password",
                     "username")
VALUES ('f', '2023-11-02 11:43:24.724871', 1, '2023-11-02 11:43:24.724871', 'Admin Admin', 'admin@prueba.net', 'Admin',
        '$2a$10$vPaqZvZkz6jhb7U7k/V/v.5vprfNdOnh4sxi/qpPRkYTzPmFlI9p2', 'admin'),
       ('f', '2023-11-02 11:43:24.730431', 2, '2023-11-02 11:43:24.730431', 'User User', 'user@prueba.net', 'User',
        '$2a$12$RUq2ScW1Kiizu5K4gKoK4OTz80.DWaruhdyfi2lZCB.KeuXTBh0S.', 'user'),
       ('f', '2023-11-02 11:43:24.733552', 3, '2023-11-02 11:43:24.733552', 'Test Test', 'test@prueba.net', 'Test',
        '$2a$10$Pd1yyq2NowcsDf4Cpf/ZXObYFkcycswqHAqBndE1wWJvYwRxlb.Pu', 'test'),
       ('f', '2023-11-02 11:43:24.736674', 4, '2023-11-02 11:43:24.736674', 'Otro Otro', 'otro@prueba.net', 'otro',
        '$2a$12$3Q4.UZbvBMBEvIwwjGEjae/zrIr6S50NusUlBcCNmBd2382eyU0bS', 'otro');


CREATE TABLE "public"."categories"
(
    "is_deleted" boolean   DEFAULT false,
    "created_at" timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
    "id"         uuid                                NOT NULL,
    "name"       character varying(255)              NOT NULL,
    CONSTRAINT "categories_name_key" UNIQUE ("name"),
    CONSTRAINT "categories_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "categories" ("is_deleted", "created_at", "updated_at", "id", "name")
VALUES ('f', '2023-11-02 11:43:24.717712', '2023-11-02 11:43:24.717712', 'd69cf3db-b77d-4181-b3cd-5ca8107fb6a9',
        'Superheros'),
       ('f', '2023-11-02 11:43:24.717712', '2023-11-02 11:43:24.717712', '6dbcbf5e-8e1c-47cc-8578-7b0a33ebc154',
        'Movies'),
       ('f', '2023-11-02 11:43:24.717712', '2023-11-02 11:43:24.717712', '9def16db-362b-44c4-9fc9-77117758b5b0',
        'TV Series'),
       ('f', '2023-11-02 11:43:24.717712', '2023-11-02 11:43:24.717712', '8c5c06ba-49d6-46b6-85cc-8246c0f362bc',
        'Videogames'),
       ('f', '2023-11-02 11:43:24.717712', '2023-11-02 11:43:24.717712', 'bb51d00d-13fb-4b09-acc9-948185636f79',
        'Animals');


ALTER TABLE ONLY "public"."funkos"
    ADD CONSTRAINT "fk2fwq10nwymfv7fumctxt9vpgb" FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."user_roles"
    ADD CONSTRAINT "fk2chxp26bnpqjibydrikgq4t9e" FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE;