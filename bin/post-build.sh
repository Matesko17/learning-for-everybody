#! /bin/bash
# Script handles Jenkins post-build process
## For using this you need to setup environment variables in your Jenkins process configuration

case $deploytype in
    "full")
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        ncftp -u $stageUser -p $stagePass $stageHost << EOF
            cd $remoteProjectFolder/
            chmod 777 admin/var
            chmod 777 admin/var/cache
            chmod 777 admin/var/cache/selects
            chmod 777 admin/var/log
            chmod 777 admin/var/temp
            chmod 777 admin/var/templates_c
            chmod 777 admin/var/templates_c/default
            chmod 777 log
            chmod 777 temp
            chmod 777 temp/cache
            chmod 777 www/files/ckfiles
            chmod 777 www/files/ckfiles/.thumbs
            chmod 777 www/files/image/news
            chmod 777 www/files/image/news/gallery
            chmod 777 www/files/image/gallery
            chmod 777 www/files/image/thumbnail
            quit
EOF
        ;;
    
    "front")
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        ncftp -u $stageUser -p $stagePass $stageHost << EOF
            cd $remoteProjectFolder/
            chmod 777 log
            chmod 777 temp
            chmod 777 temp/cache
            chmod 777 www/files/ckfiles
            chmod 777 www/files/ckfiles/.thumbs
            chmod 777 www/files/image/news
            chmod 777 www/files/image/news/gallery
            chmod 777 www/files/image/gallery
            chmod 777 www/files/image/thumbnail
            quit
EOF
#        php vendor/nette/tester/src/tester.php -C tests/HeadersTest.phpt
    ;;
    
    "fast-front")
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        ncftp -u $stageUser -p $stagePass $stageHost << EOF
            cd $remoteProjectFolder/
            chmod 777 log
            chmod 777 temp
            chmod 777 temp/cache
            quit
EOF
    ;;
    
    "admin")
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        ncftp -u $stageUser -p $stagePass $stageHost << EOF
            cd $remoteProjectFolder/
            chmod 777 admin/var
            chmod 777 admin/var/cache
            chmod 777 admin/var/cache/selects
            chmod 777 admin/var/log
            chmod 777 admin/var/temp
            chmod 777 admin/var/templates_c
            chmod 777 admin/var/templates_c/default
            quit
EOF
    ;;  
    
    *)
        echo "Invalid deploy type!"
    ;;
esac
