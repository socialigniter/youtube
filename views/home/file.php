<link href="uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="swfobject.js"></script>
<script type="text/javascript" src="jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript">

$(document)ready(function() {
  $('#file_upload').uploadify({
    'uploader'  : 'uploadify.swf',
    'script'    : 'uploadify.php',
    'cancelImg' : 'cancel.png',
    'folder'    : 'uploads',
    'auto'      : true
  });
});
</script>
</head>
<body>

<input id="file_upload" name="file_upload" type="file" />
