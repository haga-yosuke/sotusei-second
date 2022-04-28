<?php
session_start();
include("functions.php");
check_session_id();
$my_id = $_SESSION["id"];
$session_id = $_SESSION["id"];
$id = $_GET["id"];

// var_dump($session_id);
// var_dump($id);
// exit();

if("$session_id" === $id){
  header("Location:mypage.php");
exit();
}

// var_dump($id);
// var_dump($my_id);
// exit();

$pdo = connect_to_db();

//--------いいねの表示に関するところ------------
//ユーザーがいいねされた総数を表示
$sql = 'SELECT SUM((good)) FROM (posts_table) WHERE user_id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$good_total = $stmt->fetch();

if($good_total["SUM((good))"] === NULL){
    $good_total["SUM((good))"] = 0;
}

//ユーザーがいいねした総数を表示////
$sql = 'SELECT COUNT(*) FROM good WHERE session_user_id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$good_to_total = $stmt->fetch();

// var_dump($good_to_total);
// exit();

//--------いいねの表示に関するところ------------


// var_dump($good_total["SUM((good))"]);
// exit();



//--------コメントの表示に関するところ------------

////////もらったコメント/////////
$sql = 'SELECT SUM((comment)) FROM (posts_table) WHERE user_id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$comment_total = $stmt->fetch();

if($comment_total["SUM((comment))"] === NULL){
    $comment_total["SUM((comment))"] = 0;
}

///////送ったコメント////////
$sql = 'SELECT COUNT(*) FROM comment_table WHERE user_id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$comment_to_total = $stmt->fetch();

// var_dump($comment_to_total);
// exit();

// if($comment_to_total["SUM((user_id))"] === NULL){
//     $comment_to_total["SUM((user_id))"] = 0;
// }
///////////////////////////////////////////////////


//--------フォローに関するところ--------------------
$sql = 'SELECT SUM((follow_to)) FROM (users_table) WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$follow_to_total = $stmt->fetch();

// var_dump($follow_to_total);
// exit();

$sql = 'SELECT SUM((follow_from)) FROM (users_table) WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$follow_from_total = $stmt->fetch();

///////////////////////////////////////////////////


///////送った回答////////
$sql = 'SELECT COUNT(*) FROM answer_table WHERE user_id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$answer_to_total = $stmt->fetch();

////////もらった回答/////////
$sql = 'SELECT SUM((answer)) FROM (question_table) WHERE user_id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$answer_total = $stmt->fetch();

if($answer_total["SUM((answer))"] === NULL){
    $answer_total["SUM((answer))"] = 0;
}
////////////////////////////////////

$sql_user = "SELECT * FROM users_table WHERE id = $id";
$stmt_user = $pdo->prepare($sql_user);


try {
  $status_user = $stmt_user->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// var_dump($stmt_user);
// exit();

foreach($stmt_user as $user):
    $prof_img = $user["prof_img"];
    $screen_img = $user["screen_img"];
    $username = $user["username"];
    $pref = $user["pref"];
    $city = $user["city"];
    $introduction = $user["introduction"];
endforeach;

// var_dump($user["introduction"]);
// exit();


$sql_post = "SELECT * FROM posts_table WHERE user_id = $id";
$stmt_post = $pdo->prepare($sql_post);


try {
  $status_post = $stmt_post->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

?>


<head>
 
    <title>ユーザーページ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <!-- <link rel="stylesheet" href="assets/css/main.css" /> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://unpkg.com/sakura.css/css/sakura-earthly.css" type="text/css"> -->
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/headers/">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/features/">
     <!-- Bootstrap core CSS -->
 <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  .bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
  }

  @media (min-width: 768px) {
    .bd-placeholder-img-lg {
      font-size: 3.5rem;
    }
  }
</style>


<!-- Custom styles for this template -->
<link href="headers.css" rel="stylesheet">
<link href="features.css" rel="stylesheet">

	</head>
	<body class="landing is-preload">
		
         <!-- ヘッダー、ナビゲーションバー、選択時の色を変える -->
      <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
          <a href="toppage.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
            <span class="fs-4"><img id="nav_l" src="images/IMG_5507.PNG" alt="アイコン" style="height:50px;">Chalk up</span>
          </a>

          <ul class="nav nav-pills">
            <li class="nav-item"><a href="mypage.php" class="nav-link" aria-current="page">マイページ</a></li>
            <li class="nav-item"><a href="search.php" class="nav-link">探す</a></li>
            <li class="nav-item"><a href="./post/post.php" class="nav-link">書く</a></li>
            <li class="nav-item"><a href="question.php" class="nav-link">質問する</a></li>
            <li class="nav-item"><a href="./background/todo_logout.php" class="nav-link">ログアウト</a></li>
          </ul>
        </header>
      </div>

        <!-- ユーザー情報一覧 -->

    <main>
        <div class="screen_div">
            <img class="screen_img" src="./setting/<?php echo "{$screen_img}"?>" >
        </div>

        <div class="prof_area">
            <div class="prof_img">
                <img class="prof_img" src="./setting/<?php echo "{$prof_img}"?>" alt="">
            </div>
            <div class="prof">

                <div class="container">
                    


                    <div class="row row-cols-auto">
                    <div class="col"><?php echo h("{$username}") ?></div>
                    </div>
                    <div class="w-100"></div>
                    
                    <div class="row row-cols-auto">
                    <div>
                    <div ><?php echo h("{$pref}") ?> | <?php echo h("$city") ?> | <?php echo h("{$user["department"]}") ?></div>
                    <br />
                    <div ><?php echo h("{$introduction}") ?></div>
                    </div>

                    </div>
                </div>

            </div>


        <p>フォロー：<?php echo "{$follow_to_total["SUM((follow_to))"]}"?></p>
        <p>フォロワー：<?php echo "{$follow_from_total["SUM((follow_from))"]}"?></p>


        </div>
        <?php 
        if ($follow_count != 0) {?>
        <a href="./background/follow_act.php?id=<?php echo "{$id}" ?>&my_id=<?php echo "{$my_id}" ?>">フォローをやめる</a>

        <?php
        } else {?>
        <a href="./background/follow_act.php?id=<?php echo "{$id}" ?>&my_id=<?php echo "{$my_id}" ?>">フォローする</a>
        <?php }?>



        <p class="point_title">活動記録：トータルポイント</p>
        <br>

        <p class="point_font">もらったいいね数：<?php echo "{$good_total["SUM((good))"]}"?></p>
        <p class="point_font">おくったいいね数：<?php echo "{$good_to_total["COUNT(*)"]}"?></p>

        <p class="point_font">もらったコメント数：<?php echo "{$comment_total["SUM((comment))"]}"?></p>
        <p class="point_font">おくったコメント数：<?php echo "{$comment_to_total ["COUNT(*)"]}"?></p>

        <p class="point_font">質問に答えた数：<?php echo "{$answer_to_total ["COUNT(*)"]}"?></p>
        <p class="point_font">質問に答えてもらった数：<?php echo "{$answer_total ["SUM((answer))"]}"?></p>
   

          <br><br>

                  <?php foreach($stmt_post as $post): ?>
                      <div style="display: flex;"><img src=./post/<?php echo "{$post["thumbnail"]}" ?> class="img-thumbnail" style="width: 200px;" alt=""> 
                      <div>
                      <div><p><b>
                <a href='article.php?id=<?php echo "{$post["post_id"]}" ?>'>
                <?php echo h("{$post["title"]}") ?></a>
                <br>
          <p>　いいね♡<?php echo "{$post["good"]}"  ?>　|　コメント<?php echo "{$post["comment"]}"  ?>　|　<?php echo "{$post["p_created_at"]}"  ?></p>
            
                </b></p></div>
                      <div><p><?php echo h("{$post["text"]}")?></p></div>
                      </div>
                      </div>
                      <br>
                  <?php endforeach ?>

     </main>
     <br><br>
    </body>

</html>


    <style>

main{
            display: flex;
            justify-content: center;
            text-align: center;
            align-items: center;
            flex-direction: column;
        }

        .screen_div{
            height: 250px;
            background-color: #dcdcdc;
            background:url(./setting/images/screen_img_defult.png);            
            background-position: center;
            background-size: cover;
            object-fit: cover; 
        }

        .screen_img{
            height: 250px;
            width: 100%;
            background-color: #dcdcdc;
            background-position: center;
            background-size: cover;
            object-fit: cover; 
        }

      .prof_img {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        border: 1px solid #000000;
        background:url(./setting/images/prof_defult.jpeg);   
        background-position: center;
        background-size: cover;       
      }

      .prof_area{
          display: flex;
      }

        #output li {
            background-color: rgb(231, 235, 218);
            list-style-type: none

        }

        #send{
            width: 150px;
        }

        #nyuuryoku{
            margin-top: 30px;
        }

        #setumei{
            font-size: 10px;
        }

        #top{
            /* width: 100%; */
            display: flex;
          justify-content: space-between;
        }

        #top h1{
            width: 100%;
        }

        #top img{
            width: 30%;
        }

        .point_title{
          font-size: 30px;
          font-weight: bold;
        }

        .point_font{
          font-size: 20px;
        }


    </style>