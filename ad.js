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

const dashboardData = {
  totalOrders: 150,
  pendingOrders: 45,
  completedOrders: 105
};

if(totalOrdersElem) totalOrdersElem.textContent = dashboardData.totalOrders;
if(pendingOrdersElem) pendingOrdersElem.textContent = dashboardData.pendingOrders;
if(completedOrdersElem) completedOrdersElem.textContent = dashboardData.completedOrders;

const ctx = document.getElementById('salesChart').getContext('2d');

const salesChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['8 AM','10 AM','12 PM','2 PM','4 PM','6 PM','8 PM'],
    datasets:[{
      label:'Orders Today',
      data:[20, 35, 50, 40, 60, 45, 30],
      backgroundColor:'rgba(79,140,255,0.7)',
      borderColor:'rgba(79,140,255,1)',
      borderWidth:1,
      borderRadius:5,
      barPercentage:0.5,
      categoryPercentage:0.7
    }]
  },
  options:{
    responsive:true,
    plugins:{ legend:{ display:false }, tooltip:{ enabled:true } },
    scales:{
      y:{ beginAtZero:true, max:70, ticks:{ stepSize:10 } },
      x:{ grid:{ display:false } }
    }
  }
});
