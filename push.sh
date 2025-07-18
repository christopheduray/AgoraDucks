rsync -av \
	--exclude .env \
	--exclude .git \
	--exclude composer.lock \
	--exclude vendor \
		. \
		root@mx.cdy.be:/var/www/clients/ducks/
