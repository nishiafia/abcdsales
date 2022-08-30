<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="page-header">
              <h2>{{ title }} </h2>
            </div>
            <div class="card-body table-responsive p-0">
              <p>{{ text }}</p>
           </div>
           </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['userData'],
        mounted() {
            console.log(this.userData['id'])
             console.log('Mounted')
        },
         data() {
            return {
             title: 'Dashboard',
             text: 'Welcome to Your Panel',
             renderComponent: true,
             teamcompanyid:this.userData.companyid,
             teamcompanies:{},
            }
        },
         methods: {
         },
          created() {
          //this.loadSwitchCompany();
    }
    }
</script>