<script>
import TagsInput from "./components/TagsInput.vue";
  
export default {
  setup(){

    const message = 'test';

    return {message};
  },
  data: () => ({
    jsFrameworks: ['react.js', 'vue.js', 'angular.js'],
    backendFrameworks: ['node.js']
  }),
  methods:{
    handleJsframeworksChange(tags){
      this.jsFrameworks = [...tags];
    },
    handleBackEndframeworksChange(tags){
      this.backendFrameworks = [...tags];
    },
  },
  
  components: {
    //TagsInput
  }
}
</script>


<template>
  <h1>{{message}}</h1>
  <h1>Your favourite frameworks</h1>
  <div>{{ jsFrameworks }}</div>
  <tags-input @change="handleJsframeworksChange" :selected-tags="jsFrameworks" />
  <div>{{ backendFrameworks }}</div>
  <tags-input @change="backendFrameworks = [...$event]" :selected-tags="backendFrameworks" />
</template>

<style>
#app{
  
}
</style>
