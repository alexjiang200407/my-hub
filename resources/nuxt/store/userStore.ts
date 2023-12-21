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
    payload: LoginJSONPayload | RegisterJSONPayload | null
}

export interface LoginJSONPayload
{
    accessToken : string,
    username : string,
    id : string
}

export interface RegisterJSONPayload
{
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

                let payload : LoginJSONPayload = (response.payload as LoginJSONPayload);
                this.$state.data = {
                    token: payload.accessToken,
                    id: payload.id,
                    friends: [],
                    username: payload.username,
                    isLoggedIn: true
                }
                return payload.accessToken;
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

                if (response.message !== "Successfully created user!")
                {
                    return false;
                }

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
        isLoggedIn: (state) => state.data?.isLoggedIn
    }
});