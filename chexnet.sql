-- Adminer 4.6.3 PostgreSQL dump

CREATE SEQUENCE cards_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;
CREATE SEQUENCE diagnoses_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;
CREATE SEQUENCE failed_jobs_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE jobs_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE migrations_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;
CREATE SEQUENCE plan_subscriptions_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;
CREATE SEQUENCE plans_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;
CREATE SEQUENCE settings_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;
CREATE SEQUENCE users_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."users" (
    "id" integer DEFAULT nextval('users_id_seq') NOT NULL,
    "name" character varying(191) NOT NULL,
    "email" character varying(191) NOT NULL,
    "password" character varying(191),
    "image" character varying(191) DEFAULT 'https://ucarecdn.com/b34e14f2-1f4a-43a9-8311-a12758bfe88f/' NOT NULL,
    "braintree_customer_id" character varying(191),
    "facebook_id" character varying(191),
    "google_id" character varying(191),
    "twitter_id" character varying(191),
    "remember_token" character varying(100),
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    "activated" boolean DEFAULT false NOT NULL,
    CONSTRAINT "users_email_unique" UNIQUE ("email"),
    CONSTRAINT "users_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE TABLE "public"."cards" (
    "id" integer DEFAULT nextval('cards_id_seq') NOT NULL,
    "user_id" integer NOT NULL,
    "token" character varying(191) NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "cards_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "cards_user_id_foreign" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE
) WITH (oids = false);

CREATE TABLE "public"."diagnoses" (
    "id" integer DEFAULT nextval('diagnoses_id_seq') NOT NULL,
    "user_id" integer NOT NULL,
    "slug" character varying(191) NOT NULL,
    "film" character varying(191) NOT NULL,
    "film_url" character varying(191) DEFAULT 'https://ucarecdn.com/640e7ab5-e3c6-46e6-9b77-b803a9bc4ff1/' NOT NULL,
    "atelectasis" double precision NOT NULL,
    "cardiomegaly" double precision NOT NULL,
    "effusion" double precision NOT NULL,
    "infiltration" double precision NOT NULL,
    "mass" double precision NOT NULL,
    "nodule" double precision NOT NULL,
    "pneumonia" double precision NOT NULL,
    "consolidation" double precision NOT NULL,
    "edema" double precision NOT NULL,
    "emphysema" double precision NOT NULL,
    "fibrosis" double precision NOT NULL,
    "pleural_thickening" double precision NOT NULL,
    "hernia" double precision NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "diagnoses_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "diagnoses_user_id_foreign" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE
) WITH (oids = false);

CREATE TABLE "public"."failed_jobs" (
    "id" bigint DEFAULT nextval('failed_jobs_id_seq') NOT NULL,
    "connection" text NOT NULL,
    "queue" text NOT NULL,
    "payload" text NOT NULL,
    "exception" text NOT NULL,
    "failed_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT "failed_jobs_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE TABLE "public"."jobs" (
    "id" bigint DEFAULT nextval('jobs_id_seq') NOT NULL,
    "queue" character varying(191) NOT NULL,
    "payload" text NOT NULL,
    "attempts" smallint NOT NULL,
    "reserved_at" integer,
    "available_at" integer NOT NULL,
    "created_at" integer NOT NULL,
    CONSTRAINT "jobs_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX "jobs_queue_index" ON "public"."jobs" USING btree ("queue");

CREATE TABLE "public"."migrations" (
    "id" integer DEFAULT nextval('migrations_id_seq') NOT NULL,
    "migration" character varying(191) NOT NULL,
    "batch" integer NOT NULL,
    CONSTRAINT "migrations_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "migrations" ("id", "migration", "batch") VALUES
(1,	'2014_10_12_000000_create_users_table',	1),
(2,	'2014_10_12_100000_create_password_resets_table',	1),
(3,	'2017_12_31_062321_create_user_activations_table',	1),
(4,	'2018_07_01_212002_create_jobs_table',	1),
(5,	'2018_07_03_110658_create_cards_table',	1),
(6,	'2018_07_04_050736_create_failed_jobs_table',	1),
(7,	'2018_07_06_112710_create_plans_table',	1),
(8,	'2018_07_07_091224_create_settings_table',	1),
(9,	'2018_07_09_154928_create_plan_subscriptions_table',	1),
(10,	'2018_07_10_001149_add_billable_columns_to_users_table',	1),
(11,	'2019_04_09_203126_create_diagnoses_table',	1);

CREATE TABLE "public"."password_resets" (
    "email" character varying(191) NOT NULL,
    "token" character varying(191) NOT NULL,
    "created_at" timestamp(0)
) WITH (oids = false);

CREATE INDEX "password_resets_email_index" ON "public"."password_resets" USING btree ("email");

CREATE TABLE "public"."plan_subscriptions" (
    "id" integer DEFAULT nextval('plan_subscriptions_id_seq') NOT NULL,
    "user_id" integer NOT NULL,
    "name" character varying(191) NOT NULL,
    "braintree_id" character varying(191) NOT NULL,
    "braintree_plan" character varying(191) NOT NULL,
    "quantity" integer NOT NULL,
    "trial_ends_at" timestamp(0),
    "ends_at" timestamp(0),
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "plan_subscriptions_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "plan_subscriptions_user_id_foreign" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE
) WITH (oids = false);

CREATE TABLE "public"."plans" (
    "id" integer DEFAULT nextval('plans_id_seq') NOT NULL,
    "name" character varying(191) NOT NULL,
    "slug" character varying(191) NOT NULL,
    "braintree_plan" character varying(191) NOT NULL,
    "cost" double precision NOT NULL,
    "description" text,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "plans_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "plans_slug_unique" UNIQUE ("slug")
) WITH (oids = false);

INSERT INTO "plans" ("id", "name", "slug", "braintree_plan", "cost", "description", "created_at", "updated_at") VALUES
(1,	'Premium Monthly Plan',	'premium-monthly-plan',	'premium-monthly',	9.99,	'Allows access to monthly premium videos adding several features to the basic CodeTube experience.',	'2019-04-12 06:20:59',	'2019-04-12 06:20:59'),
(2,	'Premium Yearly Plan',	'premium-yearly-plan',	'premium-yearly',	69.99,	'Allows yearly access to premium features including prominent services like CodeTube ad-free.',	'2019-04-12 06:20:59',	'2019-04-12 06:20:59');

CREATE TABLE "public"."settings" (
    "id" integer DEFAULT nextval('settings_id_seq') NOT NULL,
    "user_id" integer NOT NULL,
    "content_notification" boolean DEFAULT true NOT NULL,
    "password_notification" boolean DEFAULT true NOT NULL,
    "reply_notification" boolean DEFAULT true NOT NULL,
    "profile_visibility" boolean DEFAULT true NOT NULL,
    "email_notification" boolean DEFAULT true NOT NULL,
    "created_at" timestamp(0),
    "updated_at" timestamp(0),
    CONSTRAINT "settings_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "settings_user_id_foreign" FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE
) WITH (oids = false);

CREATE TABLE "public"."user_activations" (
    "user_id" integer NOT NULL,
    "token" character varying(191) NOT NULL,
    "created_at" timestamp(0) NOT NULL
) WITH (oids = false);

CREATE INDEX "user_activations_token_index" ON "public"."user_activations" USING btree ("token");

-- 2019-04-12 07:22:24.843784+01
