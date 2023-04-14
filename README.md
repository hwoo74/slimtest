# slim + php-di 로 잡은 기본 구축 환경... 

## 서브디렉토리로 설정할 때 nginx 설정
```
#slim framework test. 
	location /slimtest {
		index index.php;
		alias /home/devbg/slimtest/public/;
		try_files $uri $uri/ @slimtest;

		location ~ \.php$ {
			include fastcgi_params;
			fastcgi_param 	SCRIPT_FILENAME $request_filename;
			fastcgi_pass    leah2_php:9000;
			fastcgi_index 	index.php;
		}
	}
	
	location @slimtest {
		rewrite /slimtest/(.*)$ /slimtest/index.php?/$1 last;
	}
```