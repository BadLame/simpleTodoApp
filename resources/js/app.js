require('./bootstrap');

const app = new Vue({
    el: "#app",
    components: {
        "todo-list": require("./components/todo-list").default
    }
})
