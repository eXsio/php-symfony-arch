import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';

import {environment} from '@environments/environment';
import {CommentWithPost, NewComment, Page} from '@app/_models';
import {map} from "rxjs/operators";

@Injectable({providedIn: 'root'})
export class CommentService {
  constructor(private http: HttpClient) {
  }

  getLatestComments(pageNo: number) {
    return this.http.get<Page<CommentWithPost>>(`${environment.apiUrl}/api/comments/?pageNo=` + pageNo);
  }

  createComment(comment: NewComment) {
    return this.http.post<any>(`${environment.apiUrl}/api/comments/`, comment);
  }

}
