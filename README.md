# wp-image-cropping
Dynamic function for wordpress image library, using 404 ( file not found ) for media that can crop image to any size.

## Installing

The manual installation method involves downloading wp-image-cropping and uploading it to your webserver via your favourite FTP application. The WordPress codex will tell you more [here](https://wordpress.org/support/article/managing-plugins/#manual-plugin-installation) - https://wordpress.org/support/article/managing-plugins/#manual-plugin-installation.

Or using git 

```
cd /path/to/your/wordpress/plugins/directory/
git clone https://github.com/haihv23/wp-image-cropping.git

```

## Getting Started

Make sure web server link Apache or Nginx point all requests to your index.php that allow plugins can analysis the image or not.
Don't worry about server can overloading and crash cause per request to web service can cache for the next request if the specified file not found in server file storage. 

## How It Work

Thinking about some thumbnail of one of your posts has link this 
```
http://wp-image-cropping.local/wp-content/uploads/2019/08/wp-image-cropping.png
``` 
but real size of image is the too large obviously can't fit in HTML file. 

Even that slow your website down by the heavy of image size. In that case, you can change the size by output the link like 
```
http://wp-image-cropping.local/wp-content/uploads/2019/08/wp-image-cropping-100x100.png
```
That all, the request comes to plugins and do crop and resize by params you set in setting page, and store the file in right place.
Next time when you do that request again, it won't load plugin any more cause image was created and store in your web server.

## License
This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details


## Authors

* **H2V DocData** - *Initial work*
