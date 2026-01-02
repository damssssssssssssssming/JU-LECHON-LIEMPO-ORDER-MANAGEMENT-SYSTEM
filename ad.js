const sidebar = document.getElementById('sidebar');
const layout = document.getElementById('layout');

function toggleSidebar() {
  sidebar.classList.toggle('active');
  layout.classList.toggle('shift');
}
document.addEventListener('click', function(e){
  const dropdown = document.querySelector('.profile-dropdown');
  if(dropdown && !dropdown.contains(e.target)){
    dropdown.classList.remove('active');
  }
});

const profile = document.querySelector('.profile-dropdown');
if(profile){
  profile.addEventListener('click', function(){
    profile.classList.toggle('active');
  });
}
const totalOrdersElem = document.getElementById('totalOrders');
const pendingOrdersElem = document.getElementById('pendingOrders');
const completedOrdersElem = document.getElementById('completedOrders');

if(totalOrdersElem) totalOrdersElem.textContent = parseInt(totalOrdersElem.dataset.value || totalOrdersElem.textContent);
if(pendingOrdersElem) pendingOrdersElem.textContent = parseInt(pendingOrdersElem.dataset.value || pendingOrdersElem.textContent);
if(completedOrdersElem) completedOrdersElem.textContent = parseInt(completedOrdersElem.dataset.value || completedOrdersElem.textContent);

const ctx = document.getElementById('ordersChart').getContext('2d');
const dateRange = document.getElementById('dateRange');

let ordersChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [],
    datasets: [{
      label: 'Orders',
      data: [],
      backgroundColor: [],
      borderColor: 'rgba(79,140,255,1)',
      borderWidth: 1,
      borderRadius: 5,
      barPercentage: 0.5,
      categoryPercentage: 0.7
    }]
  },
  options:{
    responsive:true,
    plugins:{
      legend:{ display:false },
      tooltip:{ enabled:true }
    },
    scales:{
      y:{
        beginAtZero:true,
        ticks:{ stepSize:1 }
      },
      x:{ grid:{ display:false } }
    }
  }
});

function fetchChart(days=7){
  fetch(`fetch_orders_chart.php?days=${days}`)
    .then(res => res.json())
    .then(data => {
      ordersChart.data.labels = data.labels;
      ordersChart.data.datasets[0].data = data.values;
      ordersChart.data.datasets[0].backgroundColor = data.values.map(v => {
        if(v <= 2) return 'red';    
        if(v <= 5) return 'blue'; 
        return 'green';             
      });
      ordersChart.update();
    })
    .catch(err => console.error("Error fetching chart data:", err));
}

document.addEventListener('DOMContentLoaded', () => {
  if(dateRange){
    fetchChart(dateRange.value);
    dateRange.addEventListener('change', () => fetchChart(dateRange.value));
  } else {
    fetchChart(7); 
  }
});
