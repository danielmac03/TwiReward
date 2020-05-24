CREATE TABLE rewards(
    id int(255) auto_increment not null,
    user_id int(255) not null,
    name varchar(255) not null,
    content varchar(350) not null,
    type varchar(255) not null,
    follows bit DEFAULT 0 null,
    subscribers bit DEFAULT 0 null,
    create_datetime datetime not null,
    CONSTRAINT pk_rewards PRIMARY KEY (id)
);