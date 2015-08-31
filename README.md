Backup-Pi


Backing up and Restoring your Raspberry Pi's SD Card using Raspberry Pi.

# Download.
- for ***8Gbyte SD card image*** (with 4Gbyte backup files area):
http://ovh.to/Y7sjDA8
- for ***16Gbyte SD card image*** (with 11Gbyte backup files area);
http://ovh.to/XAycnsG

# Preparation.
- Additional 8Gbyte or 16Gbyte SD card for this application, differ from buckupping SD card.
- USB SD card reader dongle.

# How to install.
Download a image file mentioned above, write on the additional SD card by ***dd*** command.

# How to use.
## Preparation.
Prepare follows:

- USB card reader.
- "Backup Pi" installed SD card.
![2015-08-28 15.42.45.s.jpg](https://qiita-image-store.s3.amazonaws.com/0/46544/c42e40cb-9672-e849-6192-85480c564418.jpeg "2015-08-28 15.42.45.s.jpg")

## Setup.
1. Remove backupping SD card from RPi, and put it to the USB Card reader.
![2015-08-28 15.43.19.s.jpg](https://qiita-image-store.s3.amazonaws.com/0/46544/7d43e35a-6f68-9486-5db8-52cfb9ed85a3.jpeg "2015-08-28 15.43.19.s.jpg")

![2015-08-28 15.43.29.s.jpg](https://qiita-image-store.s3.amazonaws.com/0/46544/498d1f62-cca2-c555-8cd7-67ecccb082d6.jpeg "2015-08-28 15.43.29.s.jpg")

2. Put "Backup Pi" installed SD card to the RPi.
![2015-08-28 15.43.29-2.s.jpg](https://qiita-image-store.s3.amazonaws.com/0/46544/d861943c-4e64-36e9-b482-64e25ced74fc.jpeg "2015-08-28 15.43.29-2.s.jpg")

3. Put the card reader to the USB interface.
![2015-08-28 15.43.49.s.jpg](https://qiita-image-store.s3.amazonaws.com/0/46544/5f5f1637-50e6-1722-efed-18a8eb6a80fd.jpeg "2015-08-28 15.43.49.s.jpg")


4. Finish, then start RPi.
![2015-08-28 15.44.00.s.jpg](https://qiita-image-store.s3.amazonaws.com/0/46544/efbed4b6-3993-5520-d536-58248cdc50cd.jpeg "2015-08-28 15.44.00.s.jpg")

## Backup / Restor Application.
The application is running on the web. So, you can use this as follows:
- with RPi Desktop, open ***http://localhost*** by ephemeral web browser on the RPi desktop.
or
- with network connecting RPi, open ***http://gc02.local*** by your PC, tablet, and other devices which can connect the RPi.

### Backup.
![スクリーンショット 2015-08-28 16.08.48.png](https://qiita-image-store.s3.amazonaws.com/0/46544/ef3d953b-4b6b-7288-bad4-e9b7a17bc209.png "スクリーンショット 2015-08-28 16.08.48.png")

![スクリーンショット 2015-08-28 16.08.59.png](https://qiita-image-store.s3.amazonaws.com/0/46544/28c41c96-2ad1-ab78-dcf1-45e6d6651b0e.png "スクリーンショット 2015-08-28 16.08.59.png")


![スクリーンショット 2015-08-28 16.09.10.png](https://qiita-image-store.s3.amazonaws.com/0/46544/c204e849-35a3-8f56-5132-a64e64d118db.png "スクリーンショット 2015-08-28 16.09.10.png")

### Restore.
![スクリーンショット 2015-08-28 16.09.37.png](https://qiita-image-store.s3.amazonaws.com/0/46544/b6bf902c-e1e6-969d-3e1b-3546c2faf7e3.png "スクリーンショット 2015-08-28 16.09.37.png")
![スクリーンショット 2015-08-28 16.09.52.png](https://qiita-image-store.s3.amazonaws.com/0/46544/b459e2b1-cc87-9af7-69ce-1a13bbdc95bb.png "スクリーンショット 2015-08-28 16.09.52.png")
![スクリーンショット 2015-08-28 16.10.04.png](https://qiita-image-store.s3.amazonaws.com/0/46544/0d6f3383-4d41-231b-0727-03f952317407.png "スクリーンショット 2015-08-28 16.10.04.png")

## Files on the "Backup Pi" SD card.
Backuped file is cleating under the ***/boot/DATA*** folder. Also, restoring files are selected from this folder. You can read/write it on the "Backup Pi" SD card from ***Windoww, Mac and Linux PC*** as follows.

![スクリーンショット 2015-08-26 15.24.15.png](https://qiita-image-store.s3.amazonaws.com/0/46544/a237d1f0-6934-ddfa-6660-5ff66b7edb36.png "スクリーンショット 2015-08-26 15.24.15.png")

So, you can manage backuped files as ordinaly files.
The ***/boot*** folder is expanded as ***FAT32***, as opposed to the original [Raspbian image](https://downloads.raspberrypi.org/raspbian_latest) was ***FAT16***.

