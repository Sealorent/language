
<template>
    
  <div class="relative z-0">
    <Head title="Dashboard" />
    <h1 class="mb-8 text-3xl font-bold">Search</h1>
    <button class="pb-2 text-xl text-gray-400" @click="reset">reset</button>
    <input v-model="searchTerms" type="text" class="w-full px-2 py-3" placeholder="Search..." @keyup.enter="fetchDictionary" />
    <!-- <p class="mb-8 leading-normal">Hey there! Welcome to Ping CRM, a demo app designed to help illustrate how <a class="text-indigo-500 underline hover:text-orange-600" href="https://inertiajs.com">Inertia.js</a> works.</p> -->
    <div v-if="isLoading" class="flex items-center justify-center w-full pt-52">
      <div class="loader"/>
    </div>
    
    <!-- <div v-if="dictionaryEntry.length > 0" class="py-2">
      <div class="grid grid-cols-4 gap-4">
        <div v-for="(item,index) in dictionaryEntry" :key="index">
          <div class="p-4 border rounded-lg shadow-md">
            <div class="flex flex-col justify-between">
              <div class="flex items-center">
                <h2 class="text-xl font-semibold">{{ item.word }} <span class="text-sm">{{ item.phonetics[0].text }}</span></h2>
                <audio ref="audioElement" :src="item.phonetics[0].audio"/>
                <div class="ml-auto" style="width: 24px; height: 24px;" @click="playSound(index)">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M192 160H96c-13 0-24 11-24 24v144c0 13 11 24 24 24h96l160 128V32L192 160zm0 144H96V184h96v120z"/>
                  </svg>
                </div>
              </div>
            </div>
            <div class="flex flex-col">
              <div v-for="mean in item.meanings" :key="mean">
                <h3 class="text-lg font-semibold">{{ mean.partOfSpeech }}</h3>
                <p class="text-gray-600">{{ mean.definitions[0].definition }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="flex items-center justify-center w-full pt-52">
      <p>Not Found</p>
    </div> -->
    <div v-if="dictionaryEntry.length > 0" class="py-2">
      <div class="p-4 border rounded-lg shadow-md">
        <div class="flex flex-col justify-between">
          <div class="flex items-center">
            <h2 class="text-xl font-semibold">{{ dictionaryEntry[0].word }} <span class="text-sm">{{ dictionaryEntry[0].phonetics[0].text }}</span></h2>
            <audio ref="audioElement" :src="dictionaryEntry[0].phonetics[0].audio"/>
            <div class="ml-auto" style="width: 24px; height: 24px;" @click="playSound()">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M192 160H96c-13 0-24 11-24 24v144c0 13 11 24 24 24h96l160 128V32L192 160zm0 144H96V184h96v120z"/>
              </svg>
            </div>
          </div>
        </div>
        <div class="flex flex-col">
            <h3 class="text-lg font-semibold">{{ dictionaryEntry[0].meanings[0].partOfSpeech }}</h3>
            <p class="text-gray-600">{{ dictionaryEntry[0].meanings[0].definitions[0].definition }}</p>
        </div>
      </div>
    </div>
    <div v-if="isLoading != true && dictionaryEntry.length < 1" class="flex items-center justify-center w-full pt-52">
      <p>Not Found</p>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import axios from 'axios'

export default {
  components: {
    Head,
  },
  layout: Layout,
  props: {
    id: Number // Define the prop type as needed
  },
  data() {
    return {
      searchTerms: '',
      dictionaryEntry: [],
      isLoading : false,
      recent : [],
    }
  },
  mounted() {
    this.getRecent();
  },
  methods: {
    async fetchDictionary() {
      this.isLoading = true;
      this.dictionaryEntry = [];
      setTimeout(() => {
        this.fetch();
      }, 1000);
      
    },
    async fetch(){
      if (this.searchTerms) {
        try {
          const response = await axios.get('https://api.dictionaryapi.dev/api/v2/entries/en/' + this.searchTerms)
          this.dictionaryEntry = response.data || null;
          this.saveRecent(this.dictionaryEntry[0]);
        } catch (error) {
          this.isLoading = false;
          console.error('Error fetching dictionary:', error);
        }
      } else {
        this.isLoading = false;
        this.dictionaryEntry = [];
      }
    },
    async getRecent(){
      try { 
        const response = await axios.get('/api/get-recent/' + this.id);
        console.log('response',response.data);
        this.recent = response.data.data;
        this.isLoading = false;
      }catch(error){
        this.recent = []
        this.isLoading = false;
        console.error('Error:', error)
      }
    },
    async saveRecent(item){
      // check if word already exist
      const check = this.recent.filter((recent) => recent.word === item.word);
      if(check.length > 0){
        this.isLoading = false;
        console.log('already exist');
        return;
      }else{
        console.log('not exist');
        // get id from inertia render data
        try { 
          const sendData = {
            user_id: this.id,
            word: item.word,
            phonetics: item.phonetics[0].text,
            audio: item.phonetics[0].audio,
            partOfSpeech: item.meanings[0].partOfSpeech,
            definition: item.meanings[0].definitions[0].definition,
          }
          const response = await axios.post('/api/add-recent', sendData);
          console.log('success');
          console.log('response',response.data);
          this.isLoading = false;
        }catch(error){
          this.isLoading = false;
          console.error('Error:', error)
        }
      }
    },
    reset(){
      this.searchTerms = '';
      this.dictionaryEntry = [];
    },
    playSound() {
      const audioElement = this.$refs.audioElement;
      audioElement.play();
    },
  },
}
</script>
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>