<template>
  <div>
    <Head title="Reports" />
    <h1 class="mb-8 text-3xl font-bold">Riwayat</h1>
    <div class="flex flex-row gap-x-10">
      <button class="px-10 py-2 text-lg border-2 border-blue-300 rounded-3xl hover:border-blue-500" :class="all === true ? 'bg-yellow-500' : 'bg-gray-200'" @click="filterAll">All</button>
      <button class="px-10 py-2 text-lg border-2 border-blue-300 rounded-3xl hover:border-blue-500" :class="all === false ? 'bg-yellow-500' : 'bg-gray-200'" @click="filterFavorite">Favorite</button>
    </div>
    <div v-if="recent.length > 0" class="py-2">
      <div class="grid grid-cols-4 gap-4">
        <div v-for="(item,index) in recent" :key="index">
          <div class="p-4 border rounded-lg shadow-md">
            <div class="flex flex-col justify-between">
              <div class="flex items-center">
                <h2 class="text-xl font-semibold">{{ item.word }} <span class="text-sm">{{ item.phonetics }}</span></h2>
                <audio ref="audioElement" :src="item.audio"/>
                <div class="ml-auto" style="width: 24px; height: 24px;" @click="playSound(index)">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M192 160H96c-13 0-24 11-24 24v144c0 13 11 24 24 24h96l160 128V32L192 160zm0 144H96V184h96v120z"/>
                  </svg>
                </div>
              </div>
            </div>
            <div class="flex flex-col">
              <h3 class="text-lg font-semibold">{{ item.partOfSpeech }}</h3>
              <p class="text-gray-600">{{ item.definition }}</p>
            </div>
            <div class="flex items-end justify-end pt-2">
              <h3 class="text-end">
                <button v-if="item.isFavourite" @click="setUnFavorite(item.id)">
                  <svg width="29" height="24" viewBox="0 0 29 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.4805 23.4857L3.04624 13.1286C-3.16804 6.91428 5.96696 -5.01714 14.4805 4.63571C22.9941 -5.01714 32.0877 6.95571 25.9148 13.1286L14.4805 23.4857Z" fill="#F20000" stroke="#FF0000" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
                <button v-else @click="setFavorite(item.id)">
                  <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.5001 25.7894L3.06577 15.4323C-3.14851 9.218 5.98649 -2.71343 14.5001 6.93942C23.0136 -2.71343 32.1072 9.25942 25.9343 15.4323L14.5001 25.7894Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
              </h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="flex items-center justify-center w-full pt-52">
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
      isLoading: true,
      recent: [],
      all : false,
      favorite: false,
    }
  },
  mounted() {
    this.all = true;
    this.fetch()
  },
  methods: {
    async fetch(){
      try { 
        const response = await axios.get(`/api/get-recent/${this.id}`);
        this.recent = response.data.data;
        this.isLoading = false;
      }catch(error){
        this.isLoading = false;
        console.error('Error:', error)
      }
    },
    async setFavorite(id){
      await axios.get(`/api/set-favorite/${id}`)
        .then(response => {
          this.fetch()
          console.log('success:', response);
        })
        .catch(error => {
          console.error('Error:', error)
        })  
    },
    async setUnFavorite(id){
      await axios.get(`/api/set-unFavorite/${id}`)
        .then(response => {
          this.fetch()
          console.log('success:', response);
        })
        .catch(error => {
          console.error('Error:', error)
        })  
    },
    filterFavorite(){
      this.favorite = true;
      this.all = false;
      this.recent = this.recent.filter(item => item.isFavourite == true);
    },
    filterAll(){
      this.favorite = false;
      this.all = true;
      this.fetch()
    },
    playSound(index) {
      const audioElement = this.$refs.audioElement[index];
      audioElement.play();
    },
  },
  
}
</script>
