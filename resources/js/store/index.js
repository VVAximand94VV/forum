import { createStore } from 'vuex';
import middleware from "./modules/middleware";
import auth from "./modules/auth";
import profile from "./modules/profile";
import post from "./modules/post";
import topic from "./modules/topic"
import forum from "./modules/forum";
import forumCategory from "./modules/forum-category";
import createTopic from  "./modules/crete-topic";
import report from "./modules/report";

const store =  createStore({
    //namespaced: true,
    modules:{
        middleware,
        auth,
        profile,
        post,
        topic,
        forum,
        forumCategory,
        createTopic,
        report,
    },
});

export default store;
