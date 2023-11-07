'use strict';

{
  const mnopen = document.getElementById('mnopen');
  const overlay = document.querySelector('.overlay');
  const mnclose = document.getElementById('mnclose');

  mnopen.addEventListener('click', () => {
    overlay.classList.add('show');
    mnopen.classList.add('hide');
  });

  mnclose.addEventListener('click', () => {
    overlay.classList.remove('show');
    mnopen.classList.remove('hide');
  });
}