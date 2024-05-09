<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST["message"];

    if (!empty($message)) {
        // Oracle 데이터베이스 연결 설정
        $conn = oci_connect('dbuser211927', '754124', 'azza.gwangju.ac.kr/orcl', 'AL32UTF8');

        // 연결 오류 확인
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        // 채팅 메시지를 DB에 저장하는 쿼리 실행
        $sql = "INSERT INTO chat_messages (message) VALUES (:message)";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":message", $message);
        oci_execute($stmt);

        oci_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>채팅 페이지</title>
  <style>
    body {
      background-color: #585858;
      color: white;
      margin-top : -10px;
    }
    body section {
      text-align: center;
    }

    /* 목차 */
    ul {
      list-style-type: none;
      margin: -10px;
      padding: 0px;
      width : 200px;
      height : 100%;
      background-color : #424242;
      overflow : auto; /* 내용이 공간 넘어갈 경우 스크롤바 생김 */
      position : fixed;
    }
    li:hover {
      background-color: #848484;
    }
    li a {
      text-decoration : none;
      padding : 10px;
      color : white;
      display : block;
      cursor: pointer;
    }
    li a.list {
      background: #1C1C1C;
    }

    ul div { /* 로그인 버튼 */
      position : fixed;
      left : 10px;
      bottom : 10px;
    }

    /* 본문 */
    a:link {
      color : white;
    }
    a:visited {
      color : white;
    }

    .date { /* 날짜 위치, 색 */
    text-align: center;
    margin: 10px 0;
    color: #999;
    }

    #chat-messages { /* 채팅창 */
        height: 560px;
        overflow-y: scroll;
        text-align : left;
      }
    .detail {
      margin-left : 200px;
    }
    #chat-input { /* 채팅 입력 */
      width: 70%;
      height: 20px;
      padding: 10px;
      font-size: 15px;
      border: 2px solid #424242;
      border-radius: 5px; /* 테두리 둥글게 */
      outline: none;
    }

    textarea, button { /* 버튼이 아래로 살짝 내려가는 문제 해결 */
      vertical-align : middle;
    }

    #send-button { /* 전송 버튼 */
      padding: 10px 20px;
      font-size: 15px;
      background-color: #1C1C1C;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    #send-button:hover {
      background-color: #848484;
    }

    .message-container { /* 채팅 뒤에 말풍선 크기 설정*/
      display: flex;
      align-items: flex-end;
      margin-bottom: 10px;
    }

    .message-container .message { /* 채팅 뒤에 말풍선 만들기 */
      background-color: #2E2E2E;
      border-radius: 10px;
      padding: 5px 10px;
      max-width: 70%;
      margin-right: 10px;
      white-space: pre-wrap; /* 많은 띄어쓰기 보이게끔 함 */
    }

    .message-container .timestamp { /* 현재 시간 색이랑 크기 변경 */
      font-size: 12px;
      color: #999;
    }

  </style>
</head>
<body>
  <header>
  </header>

  <nav>
    <ul>
  <?php
  session_start();
  if (isset($_SESSION['userid'])) {
      // 로그인이 됐을 때
      $userid = $_SESSION['userid'];
      echo '<li><a class="list" href="#">친구 목록</a></li>';
      echo '<li><a class="#"><img src="standard.png" width="20px" height="20px">나와의 채팅</a></li>';
      echo '<li><a class="#"><img src="1.png">친구1</a></li>';
      echo '<li><a class="#"><img src="2.png">친구2</a></li>';
      echo '<li><a class="#"><img src="3.png">친구3</a></li>';
      echo '<li><a class="#"><img src="standard.png" width="20px" height="20px">db로 default값을 기본프로필 사진으로 넣기</a></li>';
      echo '<li><a class="#"><img src="standard.png" width="20px" height="20px">친구5</a></li>';
      echo '<div>';
      echo '<span style="color: white;">' . $userid . '</span>';
      echo '<br>';
      echo '<form action="logout.php" method="post" style="display: inline;">';
      echo '<button type="submit" id="logout" style="background-color: transparent; color: white; border: none; cursor: pointer;">로그아웃</button>';
      echo '</form>';
      echo '</div>';
  } else {
      // 로그인이 안 됐을 때
      echo '<li><a class="list" href="#">친구 목록</a></li>';
      echo '<div>';
      echo '<form action="2. login.html"><button type="submit" id="login">로그인</button></form>';
      echo '</div>';
  }
  ?>
</ul>
  </nav>

  <section class="detail">
    <h1><a href="" style="text-decoration: none" class="main">메인 페이지</a></h1><br>

    <div id="chat-messages">
    <?php
        // Oracle 데이터베이스 연결 설정
        $conn = oci_connect('dbuser211927', '754124', 'azza.gwangju.ac.kr/orcl', 'AL32UTF8');

        // 연결 오류 확인
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

    // 채팅 메시지를 DB에서 조회하는 쿼리 실행
        $sql = "SELECT message, timestamp FROM chat_messages ORDER BY timestamp ASC";
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);

        while ($row = oci_fetch_assoc($stmt)) {
            $message = $row["MESSAGE"];
            $timestamp = $row["TIMESTAMP"];

            echo '<div class="message-container">';
            echo '<div class="message">' . nl2br(htmlspecialchars($message)) . '</div>';
            echo '<div class="timestamp">' . $timestamp . '</div>';
            echo '</div>';
        }

        oci_close($conn);
        ?>
      </div>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <textarea id="chat-input" placeholder="메시지를 입력하세요"></textarea>
    <button id="send-button" onclick="sendMessage()">전송</button>

    <script>
    // 날짜를 표시하는 함수
    function displayDate() {
      const chatMessages = document.getElementById('chat-messages');
      const lastChild = chatMessages.lastChild;
      if (!lastChild || lastChild.className !== 'date') {
        const currentDate = new Date();
        const dateString = currentDate.toLocaleDateString();
        const newDate = document.createElement('div');
        newDate.className = 'date';
        newDate.innerHTML = dateString;
        chatMessages.appendChild(newDate);
      }
    }

    // 현재 시간을 가져오는 함수
    function getCurrentTime() {
       now = new Date();
      const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      return timeString;
    }

    // 메시지 전송
    function sendMessage() {
      const input = document.getElementById('chat-input'); //chat-input 가져오기
      const message = input.value; //입력된 메시지 가져오기
      if (message) { //메시지가 비어있지 않은 경우
        const chatMessages = document.getElementById('chat-messages');

        //오늘 날짜
        const currentDate = new Date();
        const lastChild = chatMessages.lastChild;
        // 날짜가 바뀌었는지 체크
        if (!lastChild || lastChild.className === 'date') {
          displayDate();
        }

        //새로운 메시지 컨테이너 생성
        const newMessageContainer = document.createElement('div');
        newMessageContainer.classList.add('message-container');

        //새로운 메시지 생성
        const newMessage = document.createElement('div');
        newMessage.classList.add('message');
        newMessage.innerHTML = message.replace(/\n/g, '<br>'); // 엔터를 <br>태그로 변환

        //타임스탬프 생성
        const timestamp = document.createElement('div');
        timestamp.classList.add('timestamp');
        timestamp.textContent = getCurrentTime();

        //새로운 메시지 컨테이너에 메시지와 타임스탬프 추가
        newMessageContainer.appendChild(newMessage);
        newMessageContainer.appendChild(timestamp);

        //chat-messages에 새로운 메시지 컨테이너 추가
        chatMessages.appendChild(newMessageContainer);

        //입력란 초기화
        input.value = '';

        //입력란에 포커스 설정, 입력란에 다음 메시지를 바로 입력할 수 있게끔 함.
        input.focus();

        //chat-messages 스크롤 맨 아래로 이동, 새로운 메시지가 추가되면 스크롤바 자동으로 아래로 스크롤됨.
        chatMessages.scrollTop = chatMessages.scrollHeight;
      }
    }

     // 입력 필드 이벤트 처리
    const chatInput = document.getElementById('chat-input');
    chatInput.addEventListener('keydown', (event) => {
      // 컨트롤 + 엔터일 때 엔터 적용
      if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
        chatInput.value += '\n';
      }
      // 엔터 키일 때 전송 기능 수행
      else if (event.key === 'Enter') {
        event.preventDefault(); // 기본 엔터 동작 취소
        sendMessage();
      }
    });
    </script>
  </section>

  <footer>
  </footer>
  </body>
  </html>