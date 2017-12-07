create table transaction
(
	id int auto_increment primary key,
	trans_id varchar(255),
	full_name varchar(255),
	amount varchar(255),
	purpose text,
	ResponseCode varchar(255),
	RetrievalReferenceNumber varchar(255),
	ResponseDescription text,
	log_date timestamp
);
