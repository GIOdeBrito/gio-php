
#read -p "Building GIO-PHP Version: " version

VERSION=$(cat VERSION)

echo "Building version: "$VERSION

sudo docker exec -it gio-php-app tar -czvf gio-php-v$VERSION.tar.gz src/

sudo docker cp gio-php-app:/var/www/html/gio-php-v$VERSION.tar.gz .