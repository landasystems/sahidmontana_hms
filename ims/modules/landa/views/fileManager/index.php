<div id="file-uploader"></div>
 
<?php
 
$filesPath = realpath(param('pathImg'));
$filesUrl = param('urlImg');

 trace($filesPath);
 trace($filesUrl);
 
$this->widget("common.extensions.ezzeelfinder.ElFinderWidget", array(
    'selector' => "div#file-uploader",
    'clientOptions' => array(
//        'lang' => "id",
        'resizable' => false,
        'wysiwyg' => "ckeditor",
        'debug'=>TRUE,
        
    ),
    'connectorRoute' => "landa/fileManager/fileUploaderConnector",
    'connectorOptions' => array(
        'roots' => array(
            array(
                'driver'  => "LocalFileSystem",
                'path' => $filesPath,
                'URL' => $filesUrl,
                'tmbPath' => $filesPath . DIRECTORY_SEPARATOR . ".thumbs",
                'mimeDetect' => "internal",
//                'accessControl' => "rights"
            )
        )
    )
));


?>

<br/>
<div class="well">
    <ul>
        <li>Halaman ini merupakan file manager untuk website Anda, didalam area ini anda dapat melakukan operasi file (Upload, Tambah, Edit, Hapus, Preview, Rename, dll)</li>
        <li>Untuk melakukan upload file baru usahakan diletakkan pada directory <span class="label label-info">FILE</span> , karena selain directory tersebut merupakan directory yang di gunakan oleh system website.</li>
    </ul>
</div>