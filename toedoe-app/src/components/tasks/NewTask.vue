<template>
	<div class="relative">
        <input
            type="text"
            class="form-control form-control-lg padding-right-lg"
            placeholder="+ Add new task. Press enter to save."
            @keydown.enter="addNewTask"
        />
    </div>
</template>

<script setup>

	import { useTaskStore } from "../../stores/task";
	import { reactive } from 'vue';

	const store = useTaskStore();

	const newTask = reactive({
		'name': '',
		'is_completed' : false
	});

	const emit = defineEmits(['added']);

	const addNewTask = async (event) => {
		if( event.target.value.trim() )
		{
			newTask.name = event.target.value;
			event.target.value = "";
			store.handleAddedTask(newTask);
			//emit('added', newTask);

		}
	}
</script>