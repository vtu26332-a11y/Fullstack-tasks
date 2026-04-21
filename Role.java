package com.jobportal.controller;

import com.jobportal.entity.ApplicationStatus;
import com.jobportal.entity.Job;
import com.jobportal.entity.JobApplication;
import com.jobportal.entity.User;
import com.jobportal.dto.api.PageResponse;
import com.jobportal.dto.job.JobSummaryDto;
import com.jobportal.service.ApplicationService;
import com.jobportal.service.JobService;
import com.jobportal.service.UserService;
import org.springframework.web.bind.annotation.*;

import java.security.Principal;
import java.util.List;
import java.util.stream.Collectors;
import java.math.BigDecimal;

@RestController
@RequestMapping("/api")
public class ApiController {
    private final JobService jobService;
    private final UserService userService;
    private final ApplicationService applicationService;

    public ApiController(JobService jobService, UserService userService, ApplicationService applicationService) {
        this.jobService = jobService;
        this.userService = userService;
        this.applicationService = applicationService;
    }

    @GetMapping("/jobs")
    public List<Job> jobs(@RequestParam(required = false) String category,
                          @RequestParam(required = false) String location,
                          @RequestParam(required = false) String experience,
                          @RequestParam(required = false) String skills) {
        return jobService.search(category, location, experience, skills);
    }

    @GetMapping("/jobs/search")
    public PageResponse<JobSummaryDto> searchJobs(
            @RequestParam(required = false) String category,
            @RequestParam(required = false) String location,
            @RequestParam(required = false) String experience,
            @RequestParam(required = false) String skills,
            @RequestParam(required = false) String minSalary,
            @RequestParam(required = false) String maxSalary,
            @RequestParam(defaultValue = "0") int page,
            @RequestParam(defaultValue = "9") int size
    ) {
        BigDecimal min = parseBigDecimal(minSalary);
        BigDecimal max = parseBigDecimal(maxSalary);
        var jobPage = jobService.searchPaged(category, location, experience, skills, min, max, page, size);
        List<JobSummaryDto> content = jobPage.getContent().stream().map(j -> {
            JobSummaryDto dto = new JobSummaryDto();
            dto.setId(j.getId());
            dto.setTitle(j.getTitle());
            dto.setCategory(j.getCategory());
            dto.setLocation(j.getLocation());
            dto.setExperience(j.getExperience());
            dto.setSalary(j.getSalary());
            dto.setSkills(j.getSkills());
            dto.setDescription(j.getDescription());
            if (j.getEmployer() != null) {
                dto.setEmployerName(j.getEmployer().getName());
            }
            return dto;
        }).collect(Collectors.toList());

        return new PageResponse<>(
                content,
                jobPage.getNumber(),
                jobPage.getSize(),
                jobPage.getTotalElements(),
                jobPage.getTotalPages(),
                jobPage.isLast()
        );
    }

    private BigDecimal parseBigDecimal(String value) {
        if (value == null) return null;
        String trimmed = value.trim();
        if (trimmed.isEmpty()) return null;
        return new BigDecimal(trimmed);
    }

    @PostMapping("/student/jobs/{jobId}/apply")
    public JobApplication apply(@PathVariable Long jobId, Principal principal) {
        User student = userService.findByEmail(principal.getName());
        return applicationService.apply(student, jobService.getById(jobId));
    }

    @GetMapping("/student/applications")
    public List<JobApplication> studentApplications(Principal principal) {
        return applicationService.byStudent(userService.findByEmail(principal.getName()));
    }

    @PostMapping("/employer/jobs")
    public Job addJob(@RequestBody Job job, Principal principal) {
        return jobService.postJob(job, userService.findByEmail(principal.getName()));
    }

    @PutMapping("/employer/jobs/{id}")
    public Job updateJob(@PathVariable Long id, @RequestBody Job job, Principal principal) {
        job.setId(id);
        return jobService.updateJob(job, userService.findByEmail(principal.getName()));
    }

    @DeleteMapping("/employer/jobs/{id}")
    public void deleteJob(@PathVariable Long id, Principal principal) {
        jobService.deleteJob(id, userService.findByEmail(principal.getName()));
    }

    @PostMapping("/employer/applications/{id}/status")
    public JobApplication updateStatus(@PathVariable Long id, @RequestParam ApplicationStatus status) {
        return applicationService.updateStatus(id, status);
    }
}
