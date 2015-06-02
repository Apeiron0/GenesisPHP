--
-- GenesisPHP
--
-- Bitácora
--

CREATE TABLE bitacora (
    id        serial                      PRIMARY KEY,
    usuario   integer                     REFERENCES usuarios,
    fecha     timestamp without time zone DEFAULT ('now'::text)::timestamp without time zone,
    pagina    character varying(64),
    pagina_id integer,
    tipo      character(1)                NOT NULL,
    url       character varying,
    notas     text
);
