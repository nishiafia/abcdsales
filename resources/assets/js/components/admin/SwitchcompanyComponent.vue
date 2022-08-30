<template>
    <div class="container" >
        <div class="row mt-5">
            <div class="scom"> 
              <table class="switchcompany">
                <tbody>
                  <tr>
                    <td class="switchlabel">Switch Company: {{ this.userData}}</td>
                    <td> <select  name="teamcompanyid" v-model="teamcompanyid"  class="form-control" v-on:change="switchCompany"> 
                    <option v-for="scompany in teamcompanies" v-bind:value="scompany.teamcompanyname.id" :key="scompany.teamcompanyname.id">
                    {{ scompany.teamcompanyname.companyname }}
                    </option>
                    </select>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
         
          
        </div>
    </div>

</template>

<script>
    export default {
         props: ['userData'],

        data() {
            return {  
                teamcompanyid:'',
                teamcompanies:{},
               
               
            }
        },
        computed: { },
        methods: {

      
        loadSwitchCompany() {
          let headers = {
          "Sessionkey": this.userData.remember_user,
          }

           axios.get('/getswitchcompany', {headers})
            .then( response =>{
                this.teamcompanies = response.data
                 console.log("teamcompany =>", this.teamcompanies);
            });
        },
          switchCompany(event){
           let headers = {
            "Sessionkey": this.userData.remember_user,
            }
           let target = parseInt(event.target.value);
            axios.get("/updateSwitchCompany/"+target, {headers})
            .then( response =>{
            
            location.reload();

            });
         },
       
        },
      
       
    }
</script> 
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mt-5{
margin-top: 1rem !important;
}

</style>