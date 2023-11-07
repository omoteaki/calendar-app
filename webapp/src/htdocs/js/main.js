'use strict';

//カレンダーの表示を週表示にする
$('#selectW').click(function() {
  var weeknumber = $('#weeks').prop('selectedIndex');
  for (let i = 0; i < 6; i++){
    if (weeknumber == i){
      $('#week'+(i)).attr('class','weekstyle changestyle');
    } else {
      $('#week'+(i)).attr('class','none');
    }
  }
});

//カレンダーの表示を月表示にする
$('#selectM').click(function() {
  for (let i = 0; i < 6; i++){
    $('#week'+(i)).attr('class','weekstyle');
  }
});


//前の月を表示
$('#backMonth').click(function() {
  var year = $('#year').text();
  var month = $('#month').text() - 1;
  var date = new Date(year, month - 1);
  var today = new Date();
  if(month == today.getMonth() + 1){
    $('#thismonth').addClass('none');
  }else{
    $('#thismonth').removeClass('none');
  }
  $.ajax({
    url: "/main",
    type: "POST",
    data: { year: year, month: month, ajax: true},
    success: function(data, textStatus){
      $('#year').text(date.getFullYear());
      $('#month').text(date.getMonth() + 1);
      $('#contents').html(data);

    },
    error: function(xhr, textStatus, errorThrown){
      console.log("エラーが起きています");
    }
  });
});


//次の月を表示
$('#nextMonth').click(function() {
  var year = $('#year').text();
  var month = Number($('#month').text())+1;
  var date = new Date(year, month -1);
  var today = new Date();
  if(month == today.getMonth() + 1){
    $('#thismonth').addClass('none');
  }else{
    $('#thismonth').removeClass('none');
  }
  $.ajax({
    url: "/main",
    type: "POST",
    data: { year: year, month: month , ajax: true},
    success: function(data, textStatus){
      $('#year').text(date.getFullYear());
      $('#month').text(date.getMonth() +1);
      $('#contents').html(data);
    },
    error: function(xhr, textStatus, errorThrown){
      console.log("エラーが起きています");
    }
  });
});

//今月を表示
$('#mainMonth').click(function() {
  var today = new Date();
  var year = today.getFullYear();
  var month = today.getMonth() +1;
  if(month == today.getMonth() + 1){
    $('#thismonth').addClass('none');
  }else{
    $('#thismonth').removeClass('none');
  }
  $.ajax({
    url: "/main",
    type: "POST",
    data: { year: year, month: month , ajax: true},
    success: function(data, textStatus){
      $('#year').text(today.getFullYear());
      $('#month').text(today.getMonth() +1);
      $('#contents').html(data);
    },
    error: function(xhr, textStatus, errorThrown){
      console.log("エラーが起きています");
    }
  });
});



// //セレクターで選択した色に変更
// function selectboxChange() {
//   switch ($("#colors").prop("selectedIndex")) {
//     case 0:
//       $("#color").attr('href','/css/style_default.css');
//       break;
//     case 1:
//       $("#color").attr('href','/css/style_pink.css');
//       break;
//     case 2:
//       $("#color").attr('href','/css/style_blue.css');
//       break;
//   }
// }


// const open = document.getElementById('open');
// const close = document.getElementById('close');
// const datebox0 = document.getElementById('datebox0');


// open.addEventListener('click', () => {
  //   modal.classList.remove('hidden');
  //   mask.classList.remove('hidden');
  // });
  // const detailform = document.getElementById('detailform');
  // const mask = document.getElementById('mask');

//   document.getElementById('mask').addEventListener('click', () => {
//     document.getElementById('detailform').classList.add('hidden');
//   document.getElementById('mask').classList.add('hidden');
// });


$('#mask').click(function() {
  $('#detailform').addClass('hidden');
$('#mask').addClass('hidden');
});



// $('#return').click(function() {
//   $('#detailform').addClass('hidden');
//   $('#mask').addClass('hidden');
// });

for (let i = 0; i < 1000; i++){
  if($('#schedule' + (i)) != null){
    $('#schedule' + (i)).click(function() {
      $('#detailform').removeClass('hidden');
      $('#mask').removeClass('hidden');

      var schedule = i;
      // console.log(schedule);
      $.ajax({
        url: "/detail",
        type: "POST",
        data: { schedule: schedule, ajax: true},
        success: function(data, textStatus){
          $('#detailform').html(data);
        },
        error: function(xhr, textStatus, errorThrown){
          console.log("エラーが起きています");
        }
      });

    })
  }
}

for (let i = 0; i < 42; i++){
  if($('#datebox' + (i)) != null){
    $('#datebox' + (i)).click(function() {
      $('#detailform').removeClass('hidden');
      $('#mask').removeClass('hidden');

      var scheduleDate = $('#date' + (i)).text();
      // console.log(scheduleDate);
      $.ajax({
        url: "/detail",
        type: "POST",
        data: { scheduleDate: scheduleDate, ajax: true},
        success: function(data, textStatus){
          $('#detailform').html(data);
        },
        error: function(xhr, textStatus, errorThrown){
          console.log("エラーが起きています");
        }
      });

    })
  }
}
