'use strict';

{


  function valueChange(event){
    const checkValue = colors.elements['color'].value;
    // console.log(checkValue);
    if(checkValue == "1"){
      
      document.body.classList.remove(...document.body.classList);
      document.body.classList.add("default");
      // document.body.style.backgroundColor = 'yellow';
    } if(checkValue == "2"){
      document.body.classList.remove(...document.body.classList);
      document.body.classList.add("pink");
      // console.log(document.body);
      // document.body.style.backgroundColor = 'pink';
    } if(checkValue == "3"){
      document.body.classList.remove(...document.body.classList);
      document.body.classList.add("blue");
      // document.body.style.backgroundColor = 'blue';
    }

  }

  const colors = document.getElementById('colorthema');
  // console.log(colors);
  const color = colors.elements['color'];
  // console.log(color);
  colors.addEventListener('change',valueChange);
  


  
}