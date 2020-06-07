<?
// 관리자 페이지 
session_start();

// 현재 사용자가 로그인을 하지 않았을 경우 메인페이지로
if(!isset($_SESSION['carnum_front']))
{
   ?>
       <script>
           alert("로그인을 해주세요");
           location.replace("../index.php");
       </script>
   <?php   
}

// 현재 사용자가 관리자로 접속하지 않았을 경우 사용자 페이지로 이동
if($_SESSION['carnum_front'] != '관리자') {
   ?>
      <script>
         alert("권한이 없습니다");
         location.replace("<?php echo 'user_parking_pot.php'?>");
      </script>
   <?php
}
//데이터베이스 연동 
$connect = mysqli_connect("localhost", "root", "autoset", "valet") ;

//관리자가 페이지를 검색했을 경우
$search_rows = 0;
if($_GET['search']){
   //찾은 값 중에서 주차장에 주차되어 있는 값들만 데이터베이스에서 검색
   $search = $_GET['search'];
   $query ="select * from customer where out_date = '0000-00-00 00:00:00' and carnum_front LIKE '%$search'";
   $result = $connect->query($query);
   // 차량의 번호의 4자리가 공통된 차량 대수를 search_rows에 담음
   $search_rows = mysqli_num_rows($result);
}
// 데이터베이스에 나간 시간이 입력되지 않은 값들만 검색
$query ="select * from customer where out_date = '0000-00-00 00:00:00' and carnum_front = '$search'";
$result = $connect->query($query);
$search_row = mysqli_fetch_assoc($result);
// 검색한 차량의 주차위치를 area변수에 담음
$area = $search_row['park_area'].$search_row['park_loc'];

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>V.P.P</title>
        <script src="https://cdn.jsdelivr.net/npm/vue@2.5.2/dist/vue.js"></script>
        <script src="https://unpkg.com/vue-router@3.0.1/dist/vue-router.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.3.4"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link
            href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900"
            rel="stylesheet"/>
        <link href="../default.css" rel="stylesheet" type="text/css" media="all"/>
        <link href="../fonts.css" rel="stylesheet" type="text/css" media="all"/>
        <script src="../Home.js"></script>
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"> 
    </head>
    <script>
        //주차차량의 정보를 보기위해 새창을 띄울때 화면에 가운데에 띄우는 변수
        var popupX = (window.screen.width / 2) - (500 / 2);
        var popupY = (window.screen.height / 2) - (500 / 2);
    </script>
    <body>
        <!-- 왼쪽 네비게이터 -->
        <div id="page" class="container">
            <div id="header">
                <!-- 왼쪽 위 로고 -->
                <div id="logo">
                    <img src="../images/fire.jpg"/>&nbsp;&nbsp;&nbsp;
                    <a href="../index.php">V.P.P</a>
                </div>
                <div id="profile">
                  <div id = "profile_search">
                     <form id = "checkbox">
                        <input type="checkbox" id="ch1" name="차 번호" checked>
                        <label for="ch1"><span></span>차 번호</label>
                     </form>
                     <form action="admin_parking_pot.php" action="get">
                        <input type="search" id="search" name="search" placeholder="Type Full Car Number or Last 4 Number..." />
                        <button class="icon"><i class="fa fa-search"></i></button>
                     </form>
                  </div>
                  <ul>
                     <?
                     if($search_rows >= 2)
                     {
                           $query ="select * from customer where out_date = '0000-00-00 00:00:00' and carnum_front LIKE '%$search%'";
                           $result = $connect->query($query);
                           while($row = mysqli_fetch_assoc($result)){
                           $carnum = $row['carnum_front'];
                           ?>
                              <button type="button" class="find_button" onclick="location.href='admin_parking_pot.php?search=<?echo $carnum;?>'" ><div class='eff'></div><span><?echo $carnum;?></span></button>
                           <?
                     }
                     }
                     else
                     {
                        ?>
                        <!-- 새로 고침 -->
                        <div id="f5_box2"><a href ="admin_parking_pot.php" id="f5">
                           <img src='../images/f5_icon_default_circle.png' />
                        </a></div>
                        <div id = "user_image">
                           <img src="../images/user.jpg" />
                        </div>
                        <div class="car_num">
                           <li>관리자</li>
                        </div>
                        <div class="profile_content">
                           <li>누적된 주차 자동차 수 : 
                              <?php
                                 $query ="select * from customer";
                                 $result_set = mysqli_query($connect, $query);
                                 $count = mysqli_num_rows($result_set);
                                 echo $count.'<br>';
                              ?>
                           </li>
                           <li>현재 주차 된 자동차 수 : 
                              <?php
                                 $query ="select * from customer where out_date = '0000-00-00 00:00:00'";
                                 $result_set = mysqli_query($connect, $query);
                                 $count = mysqli_num_rows($result_set);
                                 echo $count.'<br>';
                              ?>
                           </li>
                           <li>금일 들어온 자동차 수 : 
                              <?php
                                 $in_time_sum = date('Y-m-d');
                                 $query ="select * from customer where DATE(in_date) = '$in_time_sum'";
                                 $result_set = mysqli_query($connect, $query);
                                 $count = mysqli_num_rows($result_set);
                                 echo $count.'<br>';
                              ?>
                           </li>
                        </div>
                     
                  </ul>
                  <button class="btn_logout" onclick="location.href='../logout.php'">로그아웃</button>
                  <?}?>
               </div>
               <div id="menu">
                  <ul>
                     <li class="who_use">관리자</li>
                     <li class="current_page_item"><a href="admin_parking_pot.php" accesskey="4" title="">현재 주차장 현황</a></li>
                     <li><a href="admin_gate.php" accesskey="5" title="">출입 기록</a></li>
                     <li><a href="admin_graph.php" accesskey="6" title="">Admin_graph</a></li>
                  </ul>
               </div>
            </div>
            <div id="main">
            <div id="parking_font">
               <span class = "font_blank2">A</span>
               <span class = "font_blank1">A</span>
               <span class = "font_blank2">B</span>
               <span class = "font_blank1">B</span>
            </div>
                <div id="welcome">
                    <div class="title">
                     <!-- A구역에 table구성 -->
                     <div id = 'Atable'>
                  <table border = 1 class = "admin_parking_pot_table" >
                  <?
                     // num은 B구역에 주차된 차량 수를 저장
                     // place는 B구역에 있는지 판단하는 변수
                     $place = 'A';
                     // number로 B구역에 1~10 주차번호를 할당
                     for ($number = 1 ; $number <= 6 ; $number++) 
                     {
                        $num = 0;
                  ?>
                     <tr>
                        <!-- css에 있는 주차된 곳 class을 사용할지 if문을 사용  -->
                        <td class = 'null_a <?
                        $query ="select * from customer";
                        $result = $connect->query($query);
                        // 데이터베이스에 저장된 주차구역과 주차번호(for문으로 1씩 증가) 가져오기    
                        while($rows = mysqli_fetch_assoc($result))
                        {
                           // areas에 주차구역과 주차번호 가져오기 
                           $areas = $rows['park_area'].$rows['park_loc'];;
                           // 데이터베이스에 저장된 areas값과 테이블에 주차구역+주차번호가 일치할 경우    
                           if($number <= 6)
                              if($place.$number == $areas)
                                    {
                                    // 일치하면 차량이 있다는 것으로 css에서 class car를 사용
                                       if($place.$number == $area)
                                          echo 'parking';
                                       else
                                          echo 'car_a';
                                    // 차량이 있으면 num값을 더함(num값의 총 개수는 A구역의 총 주차 수)
                                       $num++;      
                                       break;
                                    } 
                        }?>'>
                        <!-- 주차 번호를 클릭 했을 경우 admin_car.php를 작은창으로 띄우고(화면의 정가운데) get방식으로 값을 전달함(클릭된 주차구역+주차번호를 넘겨줌)  -->
                        <span>
                           <a class = "hyper_font" href="#" onclick="window.open('admin_car.php?area=<?echo $place?>&number=<?echo $number?>', 'name', 'status=no, height=500, width=600, left='+ popupX + ', top='+ popupY);return false">
                           <?
                           if($number <= 6)
                              echo $place.$number;
        
                              // 글자는 해당 주차구역과 주차번호를 나타냄
                           }?></a>
                        </span>
                        </td>
                     </tr>
                  </table>
               </div>
               

               <div class = 'road'>
                  <table>
                     <tr>        
                        <td class = 'road_td'></td>
                     </tr>
                  </table>
               </div>


               <div id = 'Atable'>
                  <table border = 1 class = "admin_parking_pot_table" >
                  <?
                     // num은 B구역에 주차된 차량 수를 저장
                     // place는 B구역에 있는지 판단하는 변수
                     $place = 'A';
                     // number로 B구역에 1~10 주차번호를 할당
                     for ($number = 12 ; $number > 6 ; $number--) 
                     {
                        $num = 0;
                  ?>
                     <tr>
                        <!-- css에 있는 주차된 곳 class을 사용할지 if문을 사용  -->
                        <td class = 'null_a <?
                        $query ="select * from customer";
                        $result = $connect->query($query);
                        // 데이터베이스에 저장된 주차구역과 주차번호(for문으로 1씩 증가) 가져오기    
                        while($rows = mysqli_fetch_assoc($result))
                        {
                           // areas에 주차구역과 주차번호 가져오기 
                           $areas = $rows['park_area'].$rows['park_loc'];;
                           // 데이터베이스에 저장된 areas값과 테이블에 주차구역+주차번호가 일치할 경우    
                           if($number > 6)
                              if($place.$number == $areas)
                                    {
                                    // 일치하면 차량이 있다는 것으로 css에서 class car를 사용
                                       if($place.$number == $area)
                                          echo 'parking';
                                       else
                                          echo 'car_a';
                                    // 차량이 있으면 num값을 더함(num값의 총 개수는 A구역의 총 주차 수)
                                       $num++;      
                                       break;
                                    } 
                        }?>'>
                        <!-- 주차 번호를 클릭 했을 경우 admin_car.php를 작은창으로 띄우고(화면의 정가운데) get방식으로 값을 전달함(클릭된 주차구역+주차번호를 넘겨줌)  -->
                        <span>
                           <a class = "hyper_font" href="#" onclick="window.open('admin_car.php?area=<?echo $place?>&number=<?echo $number?>', 'name', 'status=no, height=500, width=600, left='+ popupX + ', top='+ popupY);return false">                                    <a class = "hyper_font" href="#" onclick="window.open('admin_car.php?area=<?echo $place?>&number=<?echo $number?>', 'name', 'status=no, height=500, width=600, left='+ popupX + ', top='+ popupY);return false">
                           <?
                           if($number > 6)
                              echo $place.$number;
        
                              //    글자는 해당 주차구역과 주차번호를 나타냄
                           }?></a>
                        </span>
                        </td>
                     </tr>
                  </table>
               </div>

               
               <div id = 'Btable'>
                  <table border = 1 class = "admin_parking_pot_table" >
                  <?
                     // num은 C구역에 주차된 차량 수를 저장
                     // place는 C구역에 있는지 판단하는 변수
                     $place = 'B';
                     // number로 C구역에 1~10 주차번호를 할당
                     for ($number = 1 ; $number <= 6 ; $number++) 
                     {
                        $num = 0;
                     ?>
                     <tr>
                        <!-- css에 있는 주차된 곳 class을 사용할지 if문을 사용  -->
                        <td class = 'null_b <?
                        $query ="select * from customer";
                        $result = $connect->query($query);
                        // 데이터베이스에 저장된 주차구역과 주차번호(for문으로 1씩 증가) 가져오기    
                        while($rows = mysqli_fetch_assoc($result))
                        {
                           // areas에 주차구역과 주차번호 가져오기 
                           $areas = $rows['park_area'].$rows['park_loc'];;
                              // 데이터베이스에 저장된 areas값과 테이블에 주차구역+주차번호가 일치할 경우    
                           if($number <= 6)
                              if($place.$number == $areas)
                                    {
                                    // 일치하면 차량이 있다는 것으로 css에서 class car를 사용
                                       if($place.$number == $area)
                                          echo 'parking';
                                       else
                                          echo 'car_b';
                                    // 차량이 있으면 num값을 더함(num값의 총 개수는 A구역의 총 주차 수)
                                       $num++;      
                                       break;
                                    } 
                           
                        }?>'>
                        <!-- 주차 번호를 클릭 했을 경우 admin_car.php를 작은창으로 띄우고(화면의 정가운데) get방식으로 값을 전달함(클릭된 주차구역+주차번호를 넘겨줌)  -->
                        <span>
                           <a class = "hyper_font" href="#" onclick="window.open('admin_car.php?area=<?echo $place?>&number=<?echo $number?>', 'name', 'status=no, height=500, width=600, left='+ popupX + ', top='+ popupY);return false">
                           <!-- 글자는 해당 주차구역과 주차번호를 나타냄 -->
                           <?
                           if($number <= 6)
                              echo $place.$number;
        
                     }?></a>
                        </span>
                        </td>
                     </tr>
                  </table>
               </div>


               <div class = 'road'>
                  <table>
                     <tr>        
                        <td class = 'road_td'></td>
                     </tr>
                  </table>
               </div>
               <div id = 'Btable'>
                  <table border = 1 class = "admin_parking_pot_table" >
                  <?
                  // num은 C구역에 주차된 차량 수를 저장
                  // place는 C구역에 있는지 판단하는 변수
                  $place = 'B';
                  // number로 C구역에 1~10 주차번호를 할당
                  for ($number = 12 ; $number > 6 ; $number--) 
                  {
                     $num = 0;
                  ?>
                     <tr>
                        <!-- css에 있는 주차된 곳 class을 사용할지 if문을 사용  -->
                        <td class = 'null_b <?
                        $query ="select * from customer";
                        $result = $connect->query($query);
                        // 데이터베이스에 저장된 주차구역과 주차번호(for문으로 1씩 증가) 가져오기    
                        while($rows = mysqli_fetch_assoc($result))
                        {
                           // areas에 주차구역과 주차번호 가져오기 
                           $areas = $rows['park_area'].$rows['park_loc'];;
                           // 데이터베이스에 저장된 areas값과 테이블에 주차구역+주차번호가 일치할 경우    
                           if($number > 6)
                              if($place.$number == $areas)
                              {
                                 // 일치하면 차량이 있다는 것으로 css에서 class car를 사용
                                 echo 'car_b';
                                 // 차량이 있으면 num값을 더함(num값의 총 개수는 C구역의 총 주차 수)
                                 $num++;      
                                 break;
                              } 
                        }?>'>
                  <!-- 주차 번호를 클릭 했을 경우 admin_car.php를 작은창으로 띄우고(화면의 정가운데) get방식으로 값을 전달함(클릭된 주차구역+주차번호를 넘겨줌)  -->
                           <span>
                              <a class = "hyper_font" href="#" onclick="window.open('admin_car.php?area=<?echo $place?>&number=<?echo $number?>', 'name', 'status=no, height=500, width=600, left='+ popupX + ', top='+ popupY);return false">
                              <!-- 글자는 해당 주차구역과 주차번호를 나타냄 -->
                              <?
                              if($number > 6)
                                 echo $place.$number;
        
                     }?>
                           </a>
                           </span>
                        </td>
                     </tr>
                  </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
    </body>
</html>