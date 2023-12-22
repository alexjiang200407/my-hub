import { defineStore } from "pinia";
import DefineUser from "../types/user";
import Cookies from "js-cookie";


export interface DefineUsersStore
{
    data: DefineUser
}


export interface AuthJSONMsg
{
    message: string,
    payload: LoginJSONPayload
}

export interface LoginJSONPayload
{
    accessToken : string,
    username : string,
    id : string
}

export interface UserJSONData
{
    id : string,
    name : string,
    email : string,
    email_verified_at : string,
    created_at : string,
    updated_at : string
}


// Data store for users
export const useUserStore = defineStore({
    id: 'user-store',
    state: () : DefineUsersStore => ({
        data: {
            token: Cookies.get("accessToken"),
            isLoggedIn: Cookies.get("accessToken") !== undefined
        }
    }),
    actions: {
        async init()
        {
            // Get the user from server
            if (Cookies.get("accessToken") !== undefined)
            {
                try 
                {
                    let response : UserJSONData = await $fetch("http://localhost:8000/api/auth/user", {
                        method: "GET",
                        headers: {
                            accept: "application/json",
                            authorization: `Bearer ${this.$state.data?.token}`
                        }
                    });

                    this.$state.data.id = response.id;
                    this.$state.data.username = response.name;
                }
                catch (error)
                {
                    console.error(error);
                }

            }
        },
        async auth(email : string, password : string) : Promise<string | null>
        {
            try
            {
                let response : AuthJSONMsg = await $fetch("http://localhost:8000/api/auth/login", {
                    method: "POST",
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });

                this.$state.data = {
                    token: response.payload.accessToken,
                    id: response.payload.id,
                    friends: [],
                    username: response.payload.username,
                    isLoggedIn: true
                }
                return response.payload.accessToken;
            }
            catch (error)
            {   
                return null;
            }            
        },
        async register(email: string, username: string, password: string) : Promise<boolean>
        {
            try
            {
                let response : AuthJSONMsg = await $fetch("http://localhost:8000/api/auth/register", {
                    method: "POST",
                    body: JSON.stringify({
                        name: username,
                        email: email,
                        password: password
                    })
                });


                return true;
            }
            catch (error)
            {
                return false;
            }
        },
        async logOut() : Promise<boolean>
        {
            try
            {
                let response : AuthJSONMsg = await $fetch("http://localhost:8000/api/auth/logout", {
                    method: "GET",
                    headers: {
                        accept: "application/json",
                        authorization: `Bearer ${this.$state.data?.token}`
                    }
                });
                console.log(response);
            }
            catch (error)
            {
                console.error(error);
                return false;
            }

            this.$state.data = {
                isLoggedIn: false
            };
            Cookies.remove("accessToken");
            
            return true;
        }
    },
    getters: {
        user: (state) => state.data,
        isLoggedIn: (state) => state.data?.isLoggedIn,
        id: (state) => state.data?.id,
        token: (state) => state.data?.token
    }
});