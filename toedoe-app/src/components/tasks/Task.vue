<template>
    <li class="list-group-item py-3" >
                                <div
                                    class="d-flex justify-content-start align-items-center"
                                >
                                    <input
                                        class="form-check-input mt-0"
                                        type="checkbox"
                                        :class="completedClass"
                                        :checked="task.is_completed == '1'"
                                        @change="markTaskCompleted"
                                    />
                                    <div
                                        class="ms-2 flex-grow-1" 
                                        :class="completedClass"
                                        title="Double click the text to edit or remove"
                                        @dblclick="$event => isEdit = true"
                                    >
                                        <div class="relative" v-if="isEdit">
                                            <input 
                                            class="editable-task" 
                                            v-focus
                                            @keyup.esc="undo" 
                                            @keyup.enter="updateTask"
                                            type="text"
                                            v-model="editingTask"
                                             />
                                        </div>
                                        <span v-else>{{ task.name }}</span>
                                    </div>
                                    <!-- <div class="task-date">24 Feb 12:00</div> -->
                                </div>
                                <TaskActions @edit="$event => isEdit = true" v-show="!isEdit" @remove="removeTask" />
                            </li>
</template>

<script setup>
    import {computed, ref} from "vue"
     import TaskActions from './TaskActions.vue';

    const props = defineProps({
        task: Object
    })

    const completedClass = computed(()=> (props.task.is_completed == '1' ? 'completed' : ''))

    const emit = defineEmits(['updated', 'completed']);

    const isEdit = ref(false);

    const vFocus = {
        mounted: (el) => el.focus()
    }

    const updateTask = (event) => {
        const updatedTask = { ...props.task, name: event.target.value };
        isEdit.value = false;
        emit('updated', updatedTask);
    }

    const editingTask = ref(props.task.name);

    const undo = ($event) =>{
        
        isEdit.value = false;
        
        editingTask.value = props.task.name;
    }

    
    const markTaskCompleted = (event) => {
        const updatedTask = { ...props.task, is_completed: props.task.is_completed=='1' ? false  : true};
        emit('completed', updatedTask);
    }

    const removeTask = (event) => {
        if(confirm('are you sure you want to remove this ?') )
        {
           
            emit('removed', props.task);
        }
        
    }

    

</script>