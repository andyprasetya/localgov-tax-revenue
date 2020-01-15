CREATE TABLE data_point (
  gid integer NOT NULL,
  codex character varying(255),
  context character varying(255),
  subcontext character varying(255),
  category character varying(255),
  subcategory character varying(255),
  notes text,
  geom geometry(Point,4326)
);
ALTER TABLE public.data_point OWNER TO pgdbadmin;
COMMENT ON TABLE data_point IS 'DATA POINT';
CREATE SEQUENCE data_point_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE public.data_point_gid_seq OWNER TO pgdbadmin;
ALTER SEQUENCE data_point_gid_seq OWNED BY data_point.gid;
ALTER TABLE ONLY data_point ALTER COLUMN gid SET DEFAULT nextval('data_point_gid_seq'::regclass);
ALTER TABLE ONLY data_point ADD CONSTRAINT data_point_pkey PRIMARY KEY (gid);
CREATE INDEX data_point_geom_gist ON data_point USING gist (geom);

CREATE TABLE data_linestring (
  gid integer NOT NULL,
  codex character varying(255),
  context character varying(255),
  subcontext character varying(255),
  category character varying(255),
  subcategory character varying(255),
  notes text,
  geom geometry(LineString,4326)
);
ALTER TABLE public.data_linestring OWNER TO pgdbadmin;
COMMENT ON TABLE data_linestring IS 'DATA LINESTRING';
CREATE SEQUENCE data_linestring_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE public.data_linestring_gid_seq OWNER TO pgdbadmin;
ALTER SEQUENCE data_linestring_gid_seq OWNED BY data_linestring.gid;
ALTER TABLE ONLY data_linestring ALTER COLUMN gid SET DEFAULT nextval('data_linestring_gid_seq'::regclass);
ALTER TABLE ONLY data_linestring ADD CONSTRAINT data_linestring_pkey PRIMARY KEY (gid);
CREATE INDEX data_linestring_geom_gist ON data_linestring USING gist (geom);

CREATE TABLE data_polygon (
  gid integer NOT NULL,
  codex character varying(255),
  context character varying(255),
  subcontext character varying(255),
  category character varying(255),
  subcategory character varying(255),
  notes text,
  geom geometry(Polygon,4326)
);
ALTER TABLE public.data_polygon OWNER TO pgdbadmin;
COMMENT ON TABLE data_polygon IS 'DATA POLYGON';
CREATE SEQUENCE data_polygon_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER TABLE public.data_polygon_gid_seq OWNER TO pgdbadmin;
ALTER SEQUENCE data_polygon_gid_seq OWNED BY data_polygon.gid;
ALTER TABLE ONLY data_polygon ALTER COLUMN gid SET DEFAULT nextval('data_polygon_gid_seq'::regclass);
ALTER TABLE ONLY data_polygon ADD CONSTRAINT data_polygon_pkey PRIMARY KEY (gid);
CREATE INDEX data_polygon_geom_gist ON data_polygon USING gist (geom);