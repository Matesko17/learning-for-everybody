#! /bin/bash
# Script handles Jenkins build process
## For using this you need to setup environment variables in your Jenkins process configuration

case $deploytype in
    "full")
        sed -i -e 's/browserSyncState = true/browserSyncState = false/g' gulpfile.js
        php composer.phar install
        node ./node_modules/gulp/bin/gulp.js $gulpStage
        #cd admin/
        #npm install
        #node ./node_modules/gulp/bin/gulp.js $gulpStage
        #bash composer-install.sh
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
    ;;
    
    "front")
        sed -i -e 's/browserSyncState = true/browserSyncState = false/g' gulpfile.js
        php composer.phar install
        node ./node_modules/gulp/bin/gulp.js $gulpStage
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
    ;;
    
    "fast-front")
        sed -i -e 's/browserSyncState = true/browserSyncState = false/g' gulpfile.js
        #php composer.phar install
        node ./node_modules/gulp/bin/gulp.js $gulpStage
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
    ;;
    
    "admin")
        cd admin/
        php composer.phar install
        node ./node_modules/gulp/bin/gulp.js $gulpStage
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
        wget -qO/dev/null $stageUrl/clean.php || true
    ;;
    
    *)
        echo "invalid parameter!"
    ;;
esac
