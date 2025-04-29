// biến lưu trữ ngày đã tìm kiếm
var dayStartSelected = "2024-1-1";
var dayEndSelected = formatDate(new Date());

// thống kê top 5 user mua hàng nhiều nhất

function loadTopUser(dayStart,dayEnd){
    let topUserTable = document.getElementById('table-top-5-user');
    topUserTable.innerHTML = "";
    let tableitem =  `<thead>
                        <tr>
                            <th>Mã Người Dùng</th>
                            <th>Tên Người Dùng</th>
                            <th>Tổng hóa đơn</th>
                            <th>Tổng chi</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>`;
    fetch(`../../admin/API/index.php?type=top5users&daystart=${dayStart}&dayend=${dayEnd}`)
        .then(response => {
            if(!response.ok){
                throw new Error("Lỗi khi lấy dữ liệu");
            }
            return response.json();
        })
        .then(data => {data.forEach((user) => {
                tableitem += 
                            `<tr>
                                <td>${user.MaTK}</td>
                                <td>${user.TenTK}</td>
                                <td>${user.Amount}</td>
                                <td>${formatCurrency(user.Total)}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-2" onclick="getInvoiceDetail(${user.MaTK})">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </td>
                            </tr>`
            });
            topUserTable.innerHTML = tableitem;
        }) 
        .catch(error => {
            console.error("Lỗi khi load top user:", error);
        });
}

//Tìm kiếm user theo khoảng thời gian
function findTopUser(){  
    let dayStart = document.getElementById('day-start-top-buy').value;
    let dayEnd = document.getElementById('day-end-top-buy').value;

    if (!dayStart || !dayEnd) {
        alert('Vui lòng chọn ngày bắt đầu và ngày kết thúc!');
        return; // Dừng thực hiện hành động
    }
    dayStartSelected = dayStart;
    dayEndSelected = dayEnd;
    loadTopUser(dayStart,dayEnd)
}

function getInvoiceDetail(id){
    window.location.href = `invoice-detail.php?daystart=${dayStartSelected}&dayend=${dayEndSelected}&id=${id}`; 
}

//format đơn vị tiền tệ
function formatCurrency(amount){
    return new Intl.NumberFormat("vi-VN",{
        style: "currency",
        currency: "VND",
    }).format(amount);
}

// thống kê doanh thu theo ngày
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('selected_date').addEventListener('change', function () {
        var selectedDate = this.value;
        console.log(selectedDate);
        loadSellOnMonth(selectedDate);
    })
})

function loadSellOnMonth(selectedDate){
    fetch('../../admin/API/index.php?type=monthlyRevenue&date=' + selectedDate)
        .then(response => {
            if (!response.ok) {
                throw new Error("Error");
            }
            return response.json();
        })
        .then(data => {
            console.log(data)
            var month = parseInt(selectedDate.split("-")[1], 10);
            var year = parseInt(selectedDate.split("-")[0], 10);
            var totalDayInMonth = new Date(year, month, 0).getDate();
  
            var days = [];
            var dailyRevenue = [];
            for (var i = 1; i <= totalDayInMonth; i++) {
                days.push(i);
                dailyRevenue.push(0);
            }
  
            data.forEach(item => {
                const day = parseInt(item.order_date.split("-")[2], 10);
                if (day >= 1 && day <= totalDayInMonth) {
                    dailyRevenue[day - 1] += parseFloat(item.total);
                }
            });
  
            // Cấu hình biểu đồ
            Chart.defaults.font.family = 'Times New Roman';
            Chart.defaults.font.size   = 18;
            Chart.defaults.color       = '#777';
    
            let myChart = document.getElementById('myChart').getContext('2d');

            if (Chart.getChart(myChart)){
                Chart.getChart(myChart).destroy();
            }
            let massPopChart = new Chart(myChart, {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Doanh thu',
                        data: dailyRevenue,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderWidth: 1,
                        borderColor: '#777',
                        hoverBorderWidth: 3,
                        hoverBorderColor: '#000'
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Doanh thu theo ngày trong tháng',
                        fontSize: 25
                    },
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            fontColor: '#000'
                        }
                    },
                    layout: {
                        padding: {
                            left: 50,
                            right: 0,
                            bottom: 0,
                            top: 0
                        }
                    },
                    tooltips: {
                        enabled: true
                    }
                }
            });
        })
        .catch(error => {
            console.error("bug: " + error);
            document.getElementById('chart-result').innerHTML = 'Không thể lấy dữ liệu.';
        });
  };
  function formatDate(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0'); // Thêm số 0 nếu < 10
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }
  
  loadTopUser('2024-01-01', formatDate(new Date()));
  console.log(new Date('2024-01-01'))
  const now = new Date();
  const month = String(now.getMonth() + 1).padStart(2, '0'); // thêm số 0 nếu cần
  const year = now.getFullYear();
  const formatted = `${month}-${year}`;
  console.log(formatted); // Ví dụ: "04-2025"
  loadSellOnMonth(formatted);
  