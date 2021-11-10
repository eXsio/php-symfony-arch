import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';

import {environment} from '@environments/environment';
import {NewPost, Page, Post, PostHeader} from '@app/_models';

@Injectable({providedIn: 'root'})
export class PostService {
  constructor(private http: HttpClient) {
  }

  getPosts(pageNo: number) {
    return this.http.get<Page<PostHeader>>(`${environment.apiUrl}/api/posts/?pageNo=` + pageNo);
  }

  getPost(postId: string) {
    return this.http.get<Post>(`${environment.apiUrl}/api/posts/` + postId);
  }

  updatePost(postId: string, newPost: NewPost) {
    return this.http.put<any>(`${environment.apiUrl}/api/admin/posts/` + postId, newPost);
  }

  createPost(newPost: NewPost) {
    return this.http.post<any>(`${environment.apiUrl}/api/admin/posts/`, newPost);
  }

  deletePost(postId: string) {
    return this.http.delete<any>(`${environment.apiUrl}/api/admin/posts/` + postId);
  }
}
