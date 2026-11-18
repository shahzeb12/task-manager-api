import { computed, ref } from "vue";
import { defineStore } from 'pinia';
import { allTasks, createTask, updateTask, completeTask, removeTask } from '../http/task-api';




export const useTaskStore = defineStore("taskStore", () => {

	const tasks = ref([]);


	const completedTasks = computed(() => tasks.value.filter((task) => task.is_completed == 1));

	const uncompletedTasks = computed(() => {
      return tasks.value.filter((task) => task.is_completed != 1);
    });

    

	const fetchAllTasks = async () => {
      const { data } = await allTasks();
      tasks.value = data.data;
    };

    const handleAddedTask = async (newtask) => {
        
        const { data: createdTask} = await createTask(newtask);

        tasks.value.unshift(createdTask.data);
    }

    const handleUpdatedTask = async (task) => {
        
        const { data: updatedTask} = await updateTask(task.id,{
            name: task.name
        });
        const currentTask = tasks.value.find( item => task.id === item.id);
        currentTask.name = updatedTask.data.name;
    }
    const handleCompletedTask = async (task) => {
        
        const { data: updatedTask} = await completeTask(task.id,{
            is_completed: task.is_completed
        });
        const currentTask = tasks.value.find( item => task.id === item.id);
        currentTask.is_completed = updatedTask.data.is_completed == '1' ? true : false;
    }

    const handleRemovedTask = async (task) => {
         const { data: updatedTask} = await removeTask(task.id);
         const index = tasks.value.findIndex( item => task.id === item.id);
         tasks.value.splice(index, 1);
    } 

    return {tasks, completedTasks, uncompletedTasks, fetchAllTasks, handleAddedTask, handleUpdatedTask, handleCompletedTask, handleRemovedTask};

});