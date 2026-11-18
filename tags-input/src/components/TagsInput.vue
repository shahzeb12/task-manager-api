<template>
	
	<div class="tags-input-wrapper">
		<span class="tag-item" v-for="(tag, index) in tags" key="index">
			<div>{{ index + ' : ' +tag }} <a class="remove-tag" href="#" @click.prevent="removeTag(index)">&times;</a></div>
		</span>
		
		
		<input class="tag-input" type="text" v-model.trim="newtag" @keydown.enter="addNewTag" @keydown.tab.prevent="addNewTag" @keydown.delete="removeLastTag" :class="{'tag-exists': isTagExists}"  />  
	</div>
</template>

<script>
export default {
	data(){
		return{
			tags: [...this.selectedTags],
			newtag: ''
		}
	},
	props: {
		selectedTags : {
			type: Array,
			required: true,
			default: () => []
		},

	}
	,
	watch: {
		newtag(newval){
			if( this.newtag.indexOf(",") > -1 )
			{
				this.newtag = this.newtag.slice(0,-1);
				this.addNewTag();
			}
		}
	},
	emits: ['change'],
	methods: {
		addNewTag(){
			if( this.newtag === '' || this.isTagExists){ return; }
			this.tags.push(this.newtag);
			this.newtag = "";
			this.$emit('change', this.tags);
		},
		removeTag(i){
			this.tags.splice(i,1);
			this.$emit('change', this.tags);
		},
		removeLastTag()
		{
			if(this.tags.length - 1  === -1)
			{
				return;
			}

			if(this.newtag.length == 0)
			{
				this.removeTag(this.tags.length - 1);
			}
			
		}
		
	},
	computed:{
		isTagExists()
		{
			return this.tags.includes(this.newtag);
		}
	}
}
</script>

<style scoped>
	
	.tag-input.tag-exists {
  color: red;
  text-decoration: line-through;
}
.tags-input-wrapper {
  background: #fff;
  padding: 0.5em;
  border: 1px solid #dbdbdb;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  min-height: 36px;
  box-sizing: border-box;
}

.tag-item {
  color: #212529;
  background-color: #eee;
  margin-right: 0.3em;
  padding: 0.25em 0.4em;
  font-size: 75%;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  padding-right: 1.25em;
  padding-left: 0.6em;
}

.tag-input {
  color: #495057;
  flex: 1;
  background: transparent;
  border: none;
}

.tag-input:focus {
  outline: none;
}
a.remove-tag {
  text-decoration: none;
}
</style>