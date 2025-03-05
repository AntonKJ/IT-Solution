const submit_btn = document.getElementById("submit");
const data_table = document.getElementById("data");
const table= document.getElementById('data_table');
const no_data= document.getElementById('no_data');

submit_btn.onclick = function (e) {
  e.preventDefault();
  data_table.style.display = "block";
  let user_id = document.getElementById('user').value;

  let xhr = new XMLHttpRequest();
  xhr.open('GET', '/data.php?user='+user_id);
  xhr.responseType = 'json';
  xhr.send();

  // Ответ
  xhr.onload = function() {
    let responseObj = xhr.response;
    // console.log(responseObj);
    document.querySelectorAll('.prev-account').forEach(e => e.remove());
    responseObj.forEach(function (e) {
      // Implement data to table
      let name_ = document.getElementById('user').options[document.getElementById('user').selectedIndex].text;
      no_data.style.display = "none";
      let td_ = document.createElement("tr");
      td_.innerHTML = "<tr><td>"+e.period+"</td><td>"+name_+"  № "+e.account_id+"</td><td>"+e.balance+"</td><td>"+e.count_transaction+"</td>";
      td_.classList.add('prev-account');
      table.childNodes[1].appendChild(td_);
      /*
      console.log(e.account_id);
      console.log(e.count_transaction);
      console.log(e.balance);
      */
    });
  };

  // TODO: implement
  //alert("Not implemented");
};
