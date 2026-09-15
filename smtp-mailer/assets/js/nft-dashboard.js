
// for NFTs Statistics
var element = document.getElementById("nft-balance-chart");
if (element !== null) {
	element.innerHTML = "";
	var nft1 = {
		chart: {
			type: 'area',
			height: 100,
			sparkline: {
				enabled: true
			}
		},
		stroke: {
			show: true,
			curve: 'smooth',
			lineCap: 'butt',
			colors: undefined,
			width: 2.5,
			dashArray: 0,
		},
		fill: {
			gradient: {
				enabled: false
			}
		},
		series: [{
			name: 'Value',
			data: [20, 14, 19, 10, 25, 20, 22, 9, 12]
		}],
		yaxis: {
			min: 0,
			show: false,
			axisBorder: {
				show: false
			},
		},
		xaxis: {
			show: false,
			axisBorder: {
				show: false
			},
		},
		colors: ["rgba(142, 84, 233, 0.75)"],
	}
	var nft1 = new ApexCharts(document.querySelector("#nft-balance-chart"), nft1);
	nft1.render();
}
function nftBalane() {
	function rgbToHex(r, g, b) {
		return "#" + ((1 << 24) | (r << 16) | (g << 8) | b).toString(16).slice(1);
	}
	nft1.updateOptions({
		colors: [
			rgbToHex(
				myVarVal.split(",")[0],
				myVarVal.split(",")[1],
				myVarVal.split(",")[2]
			),
		],
	});
}

var element = document.getElementById("nft-statistics");
if (element !== null) {
  	element.innerHTML = "";
	var options = {
		series: [
			{
				name: 'Views',
				type: 'column',
				data: [53, 61, 42, 57, 33, 42, 57, 31, 64, 72, 45, 35]
			},
			{
				name: 'Followers',
				type: 'line',
				data: [24, 50, 31, 57, 32, 63, 31, 51, 26, 47, 23, 47]
			},
		],
		chart: {
			toolbar: {
				show: false
			},
			type: 'line',
			height: 350,
		},
		grid: {
			borderColor: '#f1f1f1',
			strokeDashArray: 3
		},
		labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
		dataLabels: {
			enabled: false
		},
		stroke: {
			width: [1, 2],
			curve: ['straight', 'smooth'],
		},
		legend: {
			show: true,
			position: 'top',
		},
		xaxis: {
			axisBorder: {
				color: '#e9e9e9',
			},
		},
		plotOptions: {
			bar: {
				columnWidth: "20%",
				borderRadius: 2
			}
		},
		colors: ["rgba(132, 90, 223, 1)", "#4876e6"],
	};
	var chart2 = new ApexCharts(document.querySelector("#nft-statistics"), options);
	chart2.render();
}
function nftStatistics() {
    chart2.updateOptions({
        colors: ["rgba(" + myVarVal + ", 1)", "#4876e6"],
    })
}

// for featured collection
if (typeof Swiper !== 'undefined') {
    var swiper = new Swiper(".pagination-dynamic", {
        pagination: {
            el: ".swiper-pagination",
            dynamicBullets: true,
            clickable: true,
        },
        loop: true,
        autoplay: {
            delay: 1500,
            disableOnInteraction: false
        }
    });
}