<template>
 <div class="row">
  <h1 class="cover-heading text-center" v-show="!loaded">Discover the genius hidden inside!</h1>
  <div class="container" v-show="awaitingApi && !loaded">
    <img src="/images/loader.gif">
    <p class="lead">Imma let you finish...</p>
  </div>
  <div class="container" v-show="accessDenied">
    <h1>Access Denied!</h1>
    <p class="lead">Sadly, your secret and app combination has not verified.  Please try again with a different pairing!</p>
    <img src="/images/angry_kanye.png" />
  </div>
  <div class="container" v-show="loaded">
    <div class="button-container">
      <button class="btn btn-primary" v-on:click="getQuotes()">Get Quotes</button> <button class="btn btn-secondary" v-on:click="refreshQuotes()">Refresh Quotes!</button> 
    </div>
    
    <h4>Can you believe he said...</h4>
    <div class="quotes">
      <div class="container" v-show="awaitingApi">
        <img src="/images/loader.gif" class="small-loader">
        <p class="lead grey">Imma let you finish...</p>
      </div>
      <div v-show="quotes.length === 0 && !awaitingApi">
        <span class="quote"><super>"</super> ...Nothing.  Please click a button above. <super>"</super> - Developer</span>
        <hr />
      </div>
      <div v-for="quote in quotes">
        <span class="quote"><super>"</super> {{ quote }} <super>"</super> - Ye</span>
        <hr />
      </div>
    </div>
    <img src="/images/kanye_smile.png" class="kanye"/>
  </div>
</div>  
</template>

<style scoped>
.kanye{
  position:fixed;
  bottom:0;
  right:0;
  z-index: 1;
}
.small-loader{
  width: 50px;
}
p.lead.grey{
    color:#333;
}
.button-container{
  margin-bottom:25px;
  display:flex;
  width: 100%;
}
.button-container button{
  flex-grow:1;
  margin:0 5%;
}


hr{
    border:2px solid #111111;
}
.quotes{
  width:100%;
  padding:25px;
  box-sizing: border-box;
  background:white;
  border-radius:14px;
  margin-top:15px;
  min-width:400px;
  z-index: 2;
  position: relative;
}
  .quote{
    font-size:16px;
    padding: 10px;
    color:#333;
    
  }
  .quote super{
    font-size:25px;
    font-family: 'Times New Roman', Times, serif;
    font-style: italic;
    padding:10px;
  }
</style>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import axios from 'axios'

const app_id = import.meta.env.VITE_API_APP_ID;
const secret = import.meta.env.VITE_API_APP_SECRET;
const api_url = import.meta.env.VITE_API_URL;



export default defineComponent({
  data() {
    return {
      awaitingApi: false,
      accessDenied: false,
      loaded: false,
      accessToken: '',
      quotes: []
    }
  },
  created: function(){
    this.getAccessToken();
  },
  methods: {
    getAccessToken: function(){
       //Set state to loading
      this.awaitingApi = true;
      let encodedAuth = btoa(app_id+'='+secret);

      //Set headders
      let config = { 
        headers: {
          Authorization: encodedAuth
        }
      }

      //Get Access Token
      axios.post(api_url+'token', {}, config)
      .then(res => {
        this.awaitingApi = false;
        this.accessToken = res.data.access_token;
        this.loaded = true;
      })
      .catch((err) => {
        //I know this is bad, but I'd like to get this over the line for you by tomorrow! :) 
        alert(err);
        this.accessDenied=true;
      });
    },
    getQuotes: function(){
      this.awaitingApi = true;
      this.quotes=[];

      
      let config = {
        headers: {
          'app-id': app_id,
          Authorization: `Bearer ${this.accessToken}`,
        }
      }

      axios.get(api_url+'quote', config)
      .then(res => {
        this.awaitingApi = false;
        this.quotes = res.data.quotes
        console.log(this.quotes);
      })
      .catch((err) => {
        //I know this is bad, but I'd like to get this over the line for you by tomorrow! :) 
        alert(err);
      });

    },
    refreshQuotes: function(){ 
      this.awaitingApi = true;
      this.quotes=[];

      
      let config = {
        headers: {
          'app-id': app_id,
          Authorization: `Bearer ${this.accessToken}`,
        }
      }

      axios.get(api_url+'quote/refresh', config)
      .then(res => {
        this.awaitingApi = false;
        this.quotes = res.data.quotes
        console.log(this.quotes);
      })
      .catch((err) => {
        //I know this is bad, but I'd like to get this over the line for you by tomorrow! :) 
        alert(err);
      });
    }
  }
})

</script>


