Notes : ~ refers to the home directory of the site, not of the machine or user, in this case `~` would refer to `C:\wamp64\www\momsdata` in the context of files on the local machine or `localhost/momsdata` in the context of the webserver

how to:
1. use teh database: 1)launch wamp64; 2) open web browse and go to localhost/momsdata; 3) uploa image and specify tags
2. to see the databased http://localhost/phpmyadmin/; unam: root; psswd :''
3. to edit web pages go to ~/momsdata


**What can now be done**
	(1) Individual images (single slice dicom files, any standard image file, really any file at all) may be uploaded thru the webpage (`~/upload/upload.php`) with a gui. Any file that is not a standard image format will not display properly, but may still be uploaded.
	(2) These images appear on a gallery page (`~/gallery/gallery.php` redirected to atomaticaly from simply by `~`). A fixed size square version of each image appears along side its image id, the user who uploaded it (showing as From:username, or From:    if the image is uploaded anonymously). By default the timestamps and image tags are hidden, but uncommenting some lines in the gallery file will show these lines
	(3) There is a a search box on the gallery page. If you type in EXACTLY a tag, or a attribute:value pair, you will see ONLY those images with that tag or with that attribute:value pair. By default, all images are displayed.
	(4) You may create an account and log in to it. Images are associated with the account of the user who uploaded them.


**Some More Details**
	(I) Uploading
		(1) The upload page has four (4) user specifiable fields
			(i) A file upload box. When clicked this prompts the user to browse for a file in their file explorer. When a file is chosen, the name of the file appears, but not any sort of preview
			(ii) A text field in which the user can give a short description of the file. This is called `notes` in the database and either `notes` or `text` in the code. This is not searchable
			(iii) A series of radio boxes. If a user is logged in, he may choose either to upload the file under his username, or to upload the image anonymously. By default, he uploads it under his own name. If no user is logged in, the only 'option' presented to the user is upload anonymously. This appears as a single selected, and not un-selectable, radio box.
			(iv) A text field in which the user may specify image *tags* or *attributes*. We call something a *tag* if it gives a characteristic of the uploaded image, without that characteristic necessarily taking a certain value. For instance, we might tag an image as `dog; portrait; black-and-white;`. We call something an *attribute* if it is better understood as field taking on a certain value. For instance an image might have attributes `year:2019; modality:CT;`. Programmatically, the two are treated almost identically, in particular, a tag is treated as an attribute with a `NULL` value field. Tags and attributes are taken in a single string of text and are separated by semicolons. The order in which they appear does not matter. The tags of an image are the only searchable component.
		(2) When the user presses submit on the upload page, they are taken to another page called `~/upload/upload-processing.php`. This is where the upload is processed. It consists of the following steps
			(i) We begin by moving the uploaded file. We make our own copy and store it in `~/images/`, keeping the same file name as when uploaded, for instance, if the image `cat.png` is uploaded, we will find our copy at `~/images/cat.png`. If two files have the same name, it is possible (likely) that one will be overwritten. We take note of the filename `$image` and path `$target` for later user.
			(ii) If the image is a dicom image, we call a python script `~/upload/dicomparse.py` on the file. This script reads the header of the dicom file and puts it in the same format as the tags earlier, that is, as `attribute:value` pairs separated by colons. The output of this file is appended to the user specified tags. From then on, they are treated in exactly the same manner as user specified tags and attributes.
			(iii) We insert the name of the image (we can always reconstruct the path, because all images are stored in `~/images/`), as well as the short description (we refer to this as the `notes`) and the name of the user uploading (`NULL` if uploaded anonymously) into the database.
			(iv) We create a confirmation page that displays a messages confirming that the image has been uploaded correctly.