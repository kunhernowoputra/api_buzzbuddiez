<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>POST</title>
</head>
<body>

<div class="container" style="margin-top: 200px">
    <div class="col-md-6">
        <form action="" method="post" novalidate>
            <input type="hidden" name="token" value="1234">
            <input type="hidden" name="post_type" value="2">
            <input type="hidden" name="user_id" value="1">
            <div class="form-group">
                <textarea name="content" class="form-control" placeholder="Apa yang sedang kamu pikirkan, Buddies?"></textarea>
            </div>
            <div class="form-group">
                <select multiple class="form-control" name="interest_id">
                    <option value="1">anime</option>
                    <option value="2">berita</option>
                    <option value="3">bisnis</option>
                    <option value="4">musik</option>
                </select>
            </div>

            <input type="file" name="photo_path[]">
            <button type="submit">Submit</button>
        </form>
    </div>
</div>

</body>
</html>