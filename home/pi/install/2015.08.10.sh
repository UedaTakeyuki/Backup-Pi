# ftp
sudo apt-get install vsftpd
sudo sed -i 's|anonymous_enable=YES|local_enable=NO|g' /etc/vsftpd.conf
sudo sed -i 's|#local_enable=YES|local_enable=YES|g' /etc/vsftpd.conf
sudo sed -i 's|#write_enable=YES|write_enable=YES|g' /etc/vsftpd.conf
sudo sed -i 's|#local_umask=022|local_umask=022|g' /etc/vsftpd.conf
sudo sed -i 's|#ascii_upload_enable=YES|ascii_upload_enable=YES|g' /etc/vsftpd.conf
sudo sed -i 's|#ascii_download_enable=YES|ascii_download_enable=YES|g' /etc/vsftpd.conf
sudo sed -i 's|#chroot_local_user=YES|chroot_local_user=YES|g' /etc/vsftpd.conf
sudo sed -i 's|#chroot_list_enable=YES|chroot_list_enable=YES|g' /etc/vsftpd.conf
sudo sed -i 's|#chroot_list_file=/etc/vsftpd.chroot_list|chroot_list_file=/etc/vsftpd.chroot_list|g' /etc/vsftpd.conf

# avahi
sudo apt-get install avahi-daemon

#pip
sudo apt-get install python-pip

#smbus
sudo apt-get install i2c-tools python-smbus
#premier# echo "i2c-bcm2708" | sudo tee -a /etc/modules
#premier# sudo sh -c "echo i2c-dev >> /etc/modules"

# NGINX, php
sudo apt-get install nginx
sudo apt-get install php5-fpm
sudo sed -i 's|index index.html index.htm;|index index.php index.html index.htm;|g' /etc/nginx/sites-enabled/default
sudo sed -i 's|#location ~ \\\.php$ {|location ~ \\\.php$ {|' /etc/nginx/sites-enabled/default
sudo sed -i 's|#\tfastcgi_split_path_info |\tfastcgi_split_path_info |g' /etc/nginx/sites-enabled/default
sudo sed -i 's|#\t# With php5-fpm:|\t# With php5-fpm:|g' /etc/nginx/sites-enabled/default
sudo sed -i 's|#\tfastcgi_pass unix:/var/run/php5-fpm\.sock;|\tfastcgi_pass unix:/var/run/php5-fpm\.sock;|g' /etc/nginx/sites-enabled/default
sudo sed -i 's|#\tfastcgi_index index\.php;|\tfastcgi_index index\.php;|g' /etc/nginx/sites-enabled/default
sudo sed -i 's|#\tinclude fastcgi_params;|\tinclude fastcgi_params; }|g' /etc/nginx/sites-enabled/default

# ipython
sudo pip install ipython

# xrdp 
sudo apt-get install xrdp 
