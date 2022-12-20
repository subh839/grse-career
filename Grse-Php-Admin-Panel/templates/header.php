

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRSE Admin</title>
  <link rel="stylesheet" href="https://bootswatch.com/5/morph/bootstrap.css">
  
</head>


<body class="bg-white text-dark">

<style>
  body{
    background: #7b8ab8;
    width:100%;
    height:100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  body .login_page .card{
    width: 400px;
    height: 400px;
    background: #121b6a;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-radius: 20px;
}
body .login_page .card label{
    font-family: 'Dancing Script', cursive;
    font-size: 25px;
    margin-bottom: 20px;
    color: #ffffff;
    
}
body .login_page .card #user_input{
    width: 200px;
    height: 30px;
    border: none;
    outline: none;
    border-radius: 20px;
    text-align: center;
    font-family: 'Zen Dots', cursive;
    color: #ff4000;
    transition: all 0.2s;
}
body .login_page .card #user_input:focus{
    /* box-shadow: 2px 2px 15px -3px rgba(0,0,0,0.5),
                -3px -3px 15px -3px rgba(0,0,0,0.5); */
}
body .login_page .card .checking_btn{
    width: 100px;
    height: 40px;
    margin-top: 20px;
    font-family: 'Zen Dots', cursive;
    background: white;
    border: 2px solid #7b8ab8;
    border-radius: 20px;
    color: dodgerblue;
    transition: all 0.6s;
}
body .login_page .card .checking_btn:hover{
    color: white;
    background: dodgerblue;
}
</style>



