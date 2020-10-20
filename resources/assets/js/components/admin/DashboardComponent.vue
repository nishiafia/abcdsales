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
</template>

<script>
    export default {
        name: 'Dashboard',
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
               
            }
        },
          created() {
         // axios.get('/test').then( (response) => {
            //console.log(response);
       // });
    }
    }
</script>