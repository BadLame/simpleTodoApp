<template>
  <div>

    <div class="todo__tabs-container">
      <div :class="showCompletedTodos ? '' : 'todo__tab_active'"
           class="todo__tab"
           @click.prevent="toggleList(false)">
        <!--        @click.prevent="showCompletedTodos = false"-->
        <a class="todo__tab-text" href="#">Current</a>
      </div>
      <div :class="showCompletedTodos ? 'todo__tab_active' : ''"
           class="todo__tab"
           @click.prevent="toggleList(true)">

        <!--        @click.prevent="showCompletedTodos = true"-->
        <a class="todo__tab-text" href="#">Completed</a>
      </div>
    </div>

    <div class="todo__header">
      <span v-if="!showCompletedTodos" class="todo__header-text">{{ todos.length }} current todos</span>
      <span v-else class="todo__header-text">{{ todos.length }} completed todos</span>
      <button v-if="!showCompletedTodos" type="button" @click="showInput">Add todo</button>
    </div>

    <!--  Input container (lightbox)  -->
    <div :class="todoInputIsOn ? 'todo__input-form-container_active' : ''"
         class="js_move-to-body todo__input-form-container"
         @click.self="hideInput"
         @keydown.esc="hideInput">
      <div class="todo__input-form">
        <textarea v-model="todoInputText"
                  class="todo__input-field js_todo-input"
                  placeholder="Todo..."
                  rows="3" @keydown.ctrl.enter="updatingTodoId < 0 ? addTodo() : updateTodo()"></textarea>
        <a class="todo__close-button" href="#" @click.prevent="hideInput">
          <span class="todo__close-button-text">✕</span>
        </a>
        <a v-if="updatingTodoId < 0" class="todo__add-button" href="#" @click.prevent="addTodo">
          <span class="todo__add-button-text">+</span>
        </a>
        <a v-else class="todo__update-button" href="#" @click.prevent="updateTodo">
          <!--          <span class="todo__update-button-text"></span>-->
        </a>
      </div>
    </div>
    <!--  /Input container (lightbox)  -->

    <div v-for="(todo, i) in todos" class="todo">
      <div class="todo__item" @click="showInputWithText(todo)">
        <div class="todo__body">
          <div class="todo__text-container">
            <span class="todo__text">{{ todo.text }}</span>
          </div>
        </div>
      </div>
      <div class="todo__buttons-container">
        <div v-if="!showCompletedTodos"
             class="todo__button todo__button_complete"
             title="complete"
             @click="completeTodo(todo)">
          <span class="todo__button-text">✕</span>
        </div>
        <div v-else class="todo__button" @click="restoreTodo(todo)" title="restore">
          <span class="todo__button-text">o</span>
        </div>
        <div class="todo__button todo__button_delete" @click="deleteTodo(todo)" title="delete">
          <span class="todo__button-text">✕</span>
        </div>
      </div>
    </div>

  </div>
</template>


<script>
export default {
  props: ["current-todos"], // this.currentTodos
  data() {
    return {
      apiUrl: "/api/todos/",
      // Last added todos on top
      sortFn: (t1, t2) => t1.id > t2.id ? -1 : 1,

      // Will be filled after jump on "Completed" tab
      completedTodos: [],

      todoInputIsOn: false,
      showCompletedTodos: false,

      todoInputText: "",
      updatingTodoId: -1
    }
  },

  mounted() {
    let x = this.$el.getElementsByClassName('js_move-to-body')[0];
    x.parentNode.removeChild(x);
    document.body.appendChild(x);
  },

  computed: {
    todos() {
      let list = this.showCompletedTodos ? this.completedTodos : this.currentTodos;
      list.sort(this.sortFn);

      return list;
    }
  },

  methods: {
    // Visual block
    showInput() {
      // console.log("Show input")
      this.todoInputIsOn = true;
      setTimeout(
          () => document.getElementsByClassName("js_todo-input")[0].focus(),
          500
      );
    },

    showInputWithText(todo) {
      if (this.showCompletedTodos) return;

      this.todoInputText = todo.text;
      this.updatingTodoId = todo.id;
      this.showInput();
    },

    hideInput() {
      // console.log("Hide input")
      this.todoInputIsOn = false;
      setTimeout(() => {
        this.todoInputText = "";
        this.updatingTodoId = -1;
      }, 300);
    },

    hideTodo(todo, todolist) {
      // Dunno why, but it's really needs to refresh manually
      todolist.splice(todolist.indexOf(todo), 1);
      this.$forceUpdate();
    },

    updateTodoText(id, text) {
      this.currentTodos.find(t => t.id === id).text = text;
    },

    toggleList(showCompleted) {
      this.showCompletedTodos = showCompleted;
      if (showCompleted && this.completedTodos.length === 0)
        this.requestCompleted();
    },


    // Ajax block
    requestCompleted() {
      this.fetch("completed")
          .then(todos => this.completedTodos.push(...todos));
    },

    addTodo() {
      // console.log("Add item with text:", this.todoInputText)
      let text = this.todoInputText;

      this.fetch("store/", {text: text})
          .then(todo => this.currentTodos.unshift(todo))

      this.hideInput();
    },

    updateTodo() {
      // console.log("Update item with id="+this.updatingTodoId+"\n"+this.todoInputText);
      let id = this.updatingTodoId,
          text = this.todoInputText;

      this.fetch("update/" + id, {text: text})
          .then(status => {
              this.updateTodoText(id, text);
          });

      this.hideInput();
    },

    completeTodo(todo) {
      // console.log("Complete item i="+i+", item.id="+this.todos[i].id);
      let id = todo.id;

      this.fetch("complete/" + id)
          .then(status => {
              this.completedTodos.push(todo);
              this.hideTodo(todo, this.currentTodos);
          });
    },

    restoreTodo(todo) {
      let id = todo.id;

      this.fetch("restore/" + id)
          .then(status => {
            this.hideTodo(todo, this.completedTodos);
            this.currentTodos.push(todo);
          });
    },

    deleteTodo(todo) {
      // console.log("Delete item i="+i+", item.id="+this.todos[i].id);
      let todolist = this.curTodoList(),
          id = todo.id;

      this.fetch("delete/" + id)
          .then(status => {
              this.hideTodo(todo, todolist);
          });
    },


    // Helpers block
    async fetch(actionUrl, requestBody) {
      const url = this.apiUrl + actionUrl,
          request = {method: "post"};

      if (requestBody) {
        request.headers = {"Content-type": "application/json"};
        request.body = JSON.stringify(requestBody);
      }

      return await fetch(url, request)
          .then(response => response.json());
    },

    curTodoList() {
      return this.showCompletedTodos ? this.completedTodos : this.currentTodos;
    }
  }
}
</script>
