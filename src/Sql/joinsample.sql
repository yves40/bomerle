SELECT i.filename, i.id, k.name FROM images i, knifes k WHERE filename = 'IMG-20220613-182658-6520350c035e5.webp' 
	and i.knifes_id = k.id;
SELECT filename, i.id, name FROM images i 
	left join knifes k on i.knifes_id = k.id
	WHERE filename = 'IMG-20220613-182658-6520350c035e5.webp' 
