<?php
require("./include/redirectSession.php");
require("./include/serverCode.php");

if(isset($_POST["question"]) && $_POST["answer"]){
    $query = sqliInsertQuestion($_POST["question"], $_POST["answer"], $_SESSION['email']);
    $result = mysqli_query($link,$query);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>React Chat</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="container">
       <div id="center">
            <h1>Insert Question please</h1>
            <form name="logIn" action='clientAddQuestion.php' method='post'>
                <table id='acct_form'>
                    <tr>
                        <td class='labels'>question:</td>
                        <td><input class='resizeInput' name='question' size='100' /></td>
                    </tr>
                    <tr>
                        <td class='labels'>answer:</td>
                        <td><input class='resizeInput' name='answer' size='25' /></td>
                    </tr>
                    <tr>
                       <td>
                           <a class="btn" href="clientOptions.php"> return</a>
                       </td>
                       <td>
                           <input class="btn" type='submit' value='create question' name='s' />
                       </td>
                    </tr>
                </table>
            </form>

        </div>
    </div>
</body>
</html>
