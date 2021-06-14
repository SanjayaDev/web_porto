/*
 * DWAdmin
 */

/*
 * this is the javascipt for the dwadmin template.
 * if you want to change, please create a new javascript, 
 * because if one is missing in the original dwadmin javascript it will fall apart
 */

function sweet(icon, title, text) {
	Swal.fire({
		icon: icon,
		title: title,
		text: text
	})
}

// function promptDelete(link) {
// 	Swal.fire({
// 		title: 'Anda yakin?',
// 		text: "Item ini akan dihapus secara permanent!",
// 		icon: 'warning',
// 		showCancelButton: true,
// 		confirmButtonColor: '#3085d6',
// 		cancelButtonColor: '#d33',
// 		confirmButtonText: 'Ya, Hapus ini!'
// 	}).then((result) => {
// 		if (result.value) {
// 		 //  window.location.href = link;
// 		 $.ajax({
// 			 url: link,
// 			 type: "GET",
// 			 cache: false,
// 			 success: function(result) {
// 				 let hasil = JSON.parse(result);
// 				 if (hasil.success == 200) {
// 					 window.location.href = window.location;
// 				 } else {
// 					 Swal.fire({
// 						 title: "Gagal!",
// 						 text: hasil.message,
// 						 icon: "error"
// 					 })
// 				 }
// 			 },
// 			 error: function() {
// 				 Swal.fire({
// 						 title: "Gagal!",
// 						 text: "404 Not Found",
// 						 icon: "error"
// 					 })
// 			 }
// 		 })
// 		}
// 	})
// }

$(document).ready(function () {
	setInterval(function () {
		$(".loader").hide();
		$(".loader-overlay").hide();
	}, 1000);

	$("#sidebar-toggle, .sidebar-overlay").click(function () {
		$(".sidebar").toggleClass("sidebar-show");
		$(".sidebar-overlay").toggleClass("d-block");
	});

	$(".sidebar-items .submenu-items").click(function () {
		$(".sidebar-items .submenu-items").removeClass("active");
		$(this).toggleClass("active");
	});

	function clickMenu(goId, title) {
		$(goId).click(function (e) {
			e.preventDefault();

			$(".sidebar-items .items").removeClass("active");
			$(".sidebar-items .submenu a").removeClass("active");
			$(this).addClass("active");

		});
	}

});
localStorage.setItem('theme', 'dark');

window.console = window.console || function(t) {};
if (document.location.search.match(/type=embed/gi)) {
  window.parent.postMessage("resize", "*");
}
// console.log('Please activate dark mode, if you want to use it!');
const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');

function switchTheme(e) {
    if (e.target.checked) {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark'); //add this
    }
    else {
        document.documentElement.setAttribute('data-theme', 'light');
	localStorage.setItem('theme', 'light'); //add this
    }    
}
toggleSwitch.addEventListener('change', switchTheme, false);

const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;
if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);

    if (currentTheme === 'dark') {
        toggleSwitch.checked = true;
    }
}
