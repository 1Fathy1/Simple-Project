<?PHP
     
     $username = 'root' ; 
     $password = "" ; 
     $hostname = "localhost:3306" ; 
     $dbname = "mydb" ;
     $id = 1 ; 

     function alert($status){
        echo "<script>alert('$status')</script>";
     }

     $conn = mysqli_connect($hostname, $username , $password , $dbname) ; 
     if(!$conn){
        die("Connection failed: ". mysqli_connect_error());
     }

     if(isset($_POST['add'])){
            $name = $_POST['name'] ; 
            $username = $_POST['username'] ; 
            $email = $_POST['email'] ; 

            // check if username is Duplicate entry
            $query = "SELECT true FROM users WHERE Username = '$username';" ; 
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) > 0){
                alert('Duplicate entry');
            }
            
            else{
                    $query = "INSERT INTO users ( Name , Username , Email) VALUES ( '$name', '$username', '$email')";
                    if(mysqli_query($conn, $query)){
                        echo("<script>alert('Success')</script>");
                        header('Location: simpleProject.php') ;
                    }
            }

           
    }

     if(isset($_POST['Update'])){
         $name = $_POST['name'] ; 
         $username = $_POST['username'] ; 
         $email = $_POST['email'] ; 
         $id = $_POST['id'] ;
         

         $query = "UPDATE users SET Name = '$name', Username = '$username', Email = '$email' WHERE Id = $id" ; 

         if(mysqli_query($conn, $query)){
             alert('User updated')  ; 
         } else{
             alert('Error updating user') ; 
         }
         
         header('Location: simpleProject.php') ;

        
     }

     if(isset($_GET['del'])){
        $user_id = $_GET['id'] ; 
        $query = "DELETE FROM users WHERE id = $user_id;" ;
        
        if(mysqli_query($conn, $query)){
            alert('User deleted')  ; 
        }else{
            alert('Error deleting user') ; 
        }

        header('Location: simpleProject.php') ;
     }

     if(isset($_GET['edi'])){
        $user_id = $_GET['id'] ; 
        echo "<script>update('$user_id')</script>";
     }

     
?>


<!DOCTYPE html>
<html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Simple Project</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    height: fit-content;
                    background-color: #f1f1f1;
                }
                form{
                    background-color: darkgrey;
                    padding: 50px;
                    width: 350px;
                    margin-top:50px;
                }
                label, input {
                    display: block;
                    margin-bottom: 10px;
                }
                input{
                    width: 100%;
                    padding: 10px;
                    border: none;
                    border-radius: 5px;
                    margin-bottom: 20px;
                }

                input[type="submit"] {
                    background-color: blue;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }

                input[type="submit"]:hover{
                    background-color: darkblue;
                }

                table.display-data {
                    margin-top: 60px;
                    width: 100%;
                    border-collapse: collapse;
                }

                table.display-data thead{
                    background-color: cadetblue;
                    color: #eee;
                }

                table.display-data th, table.display-data td {
                    border: 1px solid black;
                    padding: 10px;
                }

                table.display-data tr:nth-child(even) {
                    background-color: rgb(162, 175, 185);
                }

                table.display-data tr:hover {
                    background-color: #828282;
                    color: #eee;
                }

                td a {
                    width: 48%;
                    display: inline-block;
                    height: 100%;
                    cursor: default;
                }

                td a img {
                    cursor: pointer;
                    margin-left : 50% ;
                }

               




            </style>
        </head>
        <body>
            
            <form method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>

                <label for="password">Name:</label>
                <input type="text" id="name" name="name" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <input type="hidden" id="id" name="id" value = "0" >

                <input type="submit" value="Add" name="add" id="btn_submit">
            </form>

            <table class="display-data">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Delete / Edit</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            
                                $sql = "SELECT * FROM users";
                                $result = mysqli_query($conn, $sql);
                                $usersId = [] ; 
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $usersId[$id] = $row['Id'] ; 
                                        echo " 
                                                <tr>
                                                    <td class='userId'>
                                                        " . $id . "
                                                    </td>

                                                    <td class='userName'> 
                                                        ". $row["Username"]. " 
                                                    </td>
                                                    
                                                    <td class='Name'>    
                                                        ". $row["Name"]. "
                                                    </td>
                                                    
                                                    <td class='userEmail'>   
                                                        ". $row["Email"]. "
                                                    </td>

                                                    <td>
                                                        <a id='del' href='simpleProject.php?del=true&id=". $row["Id"]. "'><img src='img/rmperson.png' width='30px' height='30px' title='remove user' ></a> 
                                                        <a class='edi' onclick='edi($id , $usersId[$id])' ". $row["Id"]. "'><img src='img/edit.png' width='30px' height='30px' title ='edit user' ></a>
                                                    </td>

                                                </tr>";
                                                $id++; 
                                    }
                                }
                                mysqli_close($conn);
                            ?>
                        
                        </tbody>
            </table>


            
        </body>

    <script type="text/javascript">
        // var edi = document.getElementById("edi");
        function edi(inputId , userId){
            let id = inputId ; 
            id--;

           // form input . 
           var username = document.getElementById("username"); 
           var name =document.getElementById("name");
           var email = document.getElementById("email");
           var inputId =document.getElementById("id") ; 
           
           // user data 
           var name_val = document.getElementsByClassName("Name")[id];
           var email_val = document.getElementsByClassName("userEmail")[id];
           var user_val = document.getElementsByClassName("userName")[id];
            
           username.value = user_val.textContent.trim();
           name.value = name_val.textContent.trim();
           email.value = email_val.textContent.trim();       
           
           var btn_submit =document.getElementById("btn_submit");
           btn_submit.value = "Update";
           btn_submit.name = "Update" ; 

           // set id 
           inputId.value = userId ;

           
        }
    </script>    
</html>